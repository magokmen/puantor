<?php
require_once "../../../include/requires.php";

if ($_POST) {

    $authName = $_POST["authName"];
    $authDescription = $_POST["authDescription"];
    if ($authName != null) {

        //CHECKBOX'LARI SAY
        $checkcount = isset($_POST["checkedDataIds"]) ? count($_POST["checkedDataIds"]) : 0;

        //EN AZ BİR ADET YETKİ SEÇİLİ İSE İŞLEME DEVAM ET
        if ($checkcount > 0) {
            $query = $ac->prepare("INSERT INTO userroles (roleName, roleDescription, isActive) VALUES (?, ?, ?)");
            $query->execute(array($authName, $authDescription, 1));
            $lastid = $ac->lastInsertId();


            if ($query) {
                //seçilil olan checkbox'larda döngüye girerek veritabanına kaydeder
                foreach ($_POST["checkedDataIds"] as $chk) {
                    $sql = $ac->prepare("INSERT INTO userauths (roleID,authID) VALUES(?,?)");
                    $sql->execute(array($lastid, $chk));
                }

                if ($sql) {
                    header("Location:index.php?p=permission-new&st=success");
                    exit();
                }
            }
        } else {
            //en az bir adet yetki seçili değilse uyarı ver
            header("Location:index.php?p=permission-new&st=authempties");
            exit();
        }

    } else {


        header("Location:index.php?p=permission-new&st=empties");
        exit();
    }
}

?>
<style>
    .card {
        word-wrap: normal;
    }
</style>

<div class="card card-outline card-info">
    <div class="card-header p-2">

        <div class="d-flex justify-content-between">


            <ul class="nav nav-pills">
                <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Yetki Grupları"
                        data-toggle="tab">Listeye Dön</a>
                </li>
            </ul>
            <?php
            $params = array(
                "method" => "update",
            
      
            );
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Proje Güncelle"
                onclick="submitFormbyAjax('projects/edit','<?php echo $params_json ?>')"
                class="btn btn-info">Kaydet</button>
        </div>

    </div><!-- /.card-header -->
    <div class="card-body">

        <form>

            <div class="row">
                <?php

                $sql = $con->prepare("SELECT * FROM authority WHERE isActive = ? ORDER BY authGroup ASC");
                $sql->execute(array(1));

                $group = 0;
                $number = 1;

                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    if ($group != $row["authGroup"]) {
                        echo $group == 0 ? "<div class='col-md-4'>" : "</div><div class='col-md-4'>";
                    }
                    ?>

                    <div class="card card-outline p-0 collapsed-card shadow-sm">
                        <div class="card-header">
                            <input type="checkbox" class="check">
                            <span class="ml-2">

                                <?php echo $row["authTitle"]; ?>
                            </span>


                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            The body of the card
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <?php
                    $group = $row["authGroup"];
                    $number += 1;
                }
                echo '</div>' ?>

            </div>

        </form>


    </div>
</div>





<script>
    $(function () {
        $('.check').bootstrapToggle({
            onstyle: "primary",
            size: "xs",
            toogle: "toogle",

        });
    })
    $("#liste").click(function () {
        var type = $("#type").val();
       
       RoutePagewithParams("roles/main","")
        $("#page-title").text($(this).data("title"));
    })
</script>

<script>
    $(document).ready(function () {
        $('#submitButton').on('click', function () {
            $('input[type="checkbox"]:checked').each(function () {
                // Checkbox'un data-id değeri
                var dataId = $(this).data('id');
                // Checkbox'un data-id değerini form verilerine ekle
                $('<input>').attr({
                    type: 'hidden',
                    name: 'checkedDataIds[]', // Gönderilecek alanın adı
                    value: dataId // Gönderilecek değer
                }).appendTo('form');
            });
            // Formu submit et
            $('#myForm').submit();
        });
        // $("input[data-bootstrap-switch]").each(function () {
        //     $(this).bootstrapSwitch('state', $(this).prop('checked'));
        // })
    });
</script>