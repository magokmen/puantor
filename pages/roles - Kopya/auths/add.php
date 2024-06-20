<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/include/requires.php";
//permtrue("personlist");
$roleId = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];

$sql = $con->prepare("SELECT * FROM userauths WHERE roleID = ?");
$sql->execute(array($roleId));
$result = $sql->fetch(PDO::FETCH_ASSOC);
if ($result) {
    $auths = json_decode($result["auths"], true); // true parametresi ile array olarak döndürür
}



// echo "role id :" . $roleId;
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
                <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Yetki Grupları" data-toggle="tab">Listeye Dön</a>
                </li>
            </ul>
            <button type="button" id="save" data-title="Yetki Tanımlama" class="btn btn-info">
                <i class="fas fa-save mr-2"></i> Kaydet</button>
        </div>
    </div><!-- /.card-header -->
    <style>
        .bg-header {
            background-color: #d9d9d9;
            font-weight: bold;
            border: 1px solid #aaa;
        }
    </style>
    <div class="card-body">

        <form id="myForm">
            <input type="hidden" value="<?php echo $roleId; ?>" id="roleId">
            <!-- <div class="container"> -->
            <?php

            $sql = $con->prepare("SELECT * FROM authority WHERE isActive = ? ORDER BY authGroup ASC, id ASC");
            $sql->execute(array(1));

            $group = 0;
            $groupCounter = 0; // Grupları saymak için
            $colors = ['bg-danger', 'bg-warning', 'bg-success', 'bg-info', 'bg-primary', 'bg-secondary', 'bg-dark', 'bg-info']; // Canlı renk dizisi
            $colorIndex = 0; // Başlangıç renk indeksi

            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $checked = isset($auths[$row["id"]]) == "on" ? "checked" : '';

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

                //  $cardClass = $row["isModule"] == 1 ? $colors[$colorIndex] . ' text-white' : 'bg-light';
                $cardClass = $row["isModule"] == 1 ? "bg-header" : 'bg-light';



                if ($group != $row["authGroup"]) {
                    echo $group != 0 ? "</div><div class='col-md-4'>" : "<div class='col-md-4'>"; // Yeni grup için yeni sütun başlat
                }
            ?>

                <div class="card card-outline p-0 collapsed-card shadow-sm <?php echo $cardClass; ?> mb-3">
                    <div class="card-header">
                        <input type="checkbox" class="check" <?php echo $checked; ?> data-id="<?php echo $row["id"] ?>">
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
                        <?php echo $row["description"] ;?>
                    </div>
                    <!-- /.card-body -->
                </div>
            <?php
                $group = $row["authGroup"];
            }
            echo '</div></div>' ?>
            <!-- </div> -->
        </form>
    </div>
</div>





<script>
    $(function() {
        $('.check').bootstrapToggle({
            onstyle: "info",
            offstyle: "danger",
            size: "xs",
            toogle: "toogle",

        });
    })
    $("#liste").click(function() {
        var type = $("#type").val();

        RoutePagewithParams("roles/main", "")
        $("#page-title").text($(this).data("title"));
    })
</script>

<script>
    $(document).ready(function() {
        var formData = {};
        var roleId = $("#roleId").val();
        $('#save').on('click', function() {
            $('input[type="checkbox"]:checked').each(function() {
                // Checkbox'un data-id değeri
                var dataId = $(this).data('id');
                // Checkbox'un değeri
                var value = $(this).val();
                // formData'ya ekle
                formData[dataId] = value;

            });
            $.ajax({
                url: "ajax.php",
                type: "POST",
                data: {
                    "data": JSON.stringify(formData), // JSON formatında gönder
                    "action": "auth-define",
                    "roleId": $('#roleId').val() // roleId inputundan alınan değeri ekle
                },
                success: function(data) {
                    var res = JSON.parse(data);
                    swal.fire({
                        title: "Başarılı!",
                        icon: "success",
                        text: res.message,
                    })
                }
            })
        });

    });
</script>