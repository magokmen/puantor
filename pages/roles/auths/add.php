<?php
require_once "../../../include/requires.php";
//permtrue("personlist");
$roleId = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];

if ($_POST) {

    //CHECKBOX'LARI SAY
    $checkcount = isset($_POST["checkedDataIds"]) ? count($_POST["checkedDataIds"]) : 0;

    //EN AZ BİR ADET YETKİ SEÇİLİ İSE İŞLEME DEVAM ET
    if ($checkcount > 0) {
        //ÖNCELİKLE TABLODAKİ YETKİLERİ SİL, SONRA TEKRAR KAYIT YAP
        $delauths = $ac->prepare("DELETE FROM userauths WHERE roleID = ?");
        $delauths->execute(array($roleId));

        //seçilil olan checkbox'larda döngüye girerek veritabanına kaydeder
        foreach ($_POST["checkedDataIds"] as $chk) {
            $sql = $ac->prepare("INSERT INTO userauths (roleID,authID) VALUES(?,?)");
            $sql->execute(array($$roleId, $chk));
        }
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
                "method" => "add",
                "id" => $roleId
            );
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Yetki Tanımlama"
                onclick="submitFormbyAjax('roles/auths/add','<?php echo $params_json ?>')"
                class="btn btn-info">Kaydet</button>
        </div>
    </div><!-- /.card-header -->
    <div class="card-body">

        <form id="myForm">

            <div class="container">
                <?php

                $sql = $con->prepare("SELECT * FROM authority WHERE isActive = ? ORDER BY authGroup ASC, id ASC");
                $sql->execute(array(1));

                $group = 0;
                $groupCounter = 0; // Grupları saymak için
                $colors = ['bg-danger', 'bg-warning', 'bg-success', 'bg-info', 'bg-primary', 'bg-secondary', 'bg-dark', 'bg-info']; // Canlı renk dizisi
                $colorIndex = 0; // Başlangıç renk indeksi
                
                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    if ($group != $row["authGroup"]) {
                        if ($groupCounter % 3 == 0 && $groupCounter != 0) {
                            echo "</div><div class='w-100 mb-4'></div><div class='row'>"; // Her üç gruptan sonra boşluk bırakıp yeni satıra geç
                        } elseif ($groupCounter % 3 == 0) {
                            echo "<div class='row'>"; // Yeni satır başlangıcı
                        }
                        $groupCounter++;
                        $colorIndex = ($colorIndex + 1) % count($colors); // Renk indeksini değiştir
                    }

                    // isModule değeri 1 ise ek bir sınıf ekle
                    $cardClass = $row["isModule"] == 1 ? $colors[$colorIndex] . ' text-white' : 'bg-light';

                    if ($group != $row["authGroup"]) {
                        echo $group != 0 ? "</div><div class='col-md-4'>" : "<div class='col-md-4'>"; // Yeni grup için yeni sütun başlat
                    }
                    ?>

                    <div class="card card-outline p-0 collapsed-card shadow-sm <?php echo $cardClass; ?> mb-3">
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
                }
                echo '</div></div>' ?>
            </div>
        </form>
    </div>
</div>





<script>
    $(function () {
        $('.check').bootstrapToggle({
            onstyle: "success",
            offstyle: "danger",
            size: "xs",
            toogle: "toogle",

        });
    })
    $("#liste").click(function () {
        var type = $("#type").val();

        RoutePagewithParams("roles/main", "")
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

    });
</script>