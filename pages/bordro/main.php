<?php


if ($_POST && $_POST['method'] == "Delete") {

    require_once $_SERVER["DOCUMENT_ROOT"] . "/include/requires.php";
     try {
        $puantaj_id = $_POST['puantaj_id'];
        $person_id = $_POST['person_id'];
        $year = $_POST['year'];
        $month = $_POST['month'];

        $up = $con->prepare("UPDATE puantaj SET viewonPayroll = ? WHERE id = ?");
        $up->execute([0, $puantaj_id]);

        $del_gelir = $con->prepare("DELETE FROM maas_gelir WHERE puantaj_id = ?");
        $del_gelir->execute([$puantaj_id]);
        
        $del_kesinti = $con->prepare("DELETE FROM wagecuts WHERE person_id = ? AND year = ? AND month = ?");
        $del_kesinti->execute([$person_id, $year, $month]);

        $res = array(
            "status" => 200,
            "message" => "Kayıt başarıyla silindi",
            "page" => "bordro/main",
            "pTitle" => "Bordro"
        );
    } catch (PDOException $ex) {
        $res = array(
            "status" => 400,
            "message" => "Error: " . $ex->getMessage()
        );
    }
    echo json_encode($res);
    return;
}

require_once $_SERVER["DOCUMENT_ROOT"] . "/plugins/datatables/datatable.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/requires.php";

if (isset($_GET["company_id"])) {
    $company_id = $_GET["company_id"];
} else  if (isset($_SESSION["companyID"])) {
    $company_id = $_SESSION["companyID"];
} else {
    $sql = $con->prepare("SELECT * FROM companies WHERE account_id = ? AND isDefault = ?");
    $sql->execute(array($account_id, 1));
    $result = $sql->fetch(PDO::FETCH_OBJ);
    $company_id = $result->id ?? 0;
}


$project_id = isset($_GET["project_id"]) ? $_GET["project_id"] : 0;
$year = isset($_GET["year"]) ? $_GET["year"] : date('Y');
$month = isset($_GET["months"]) ? $_GET["months"] : date('m');


$disabled = $company_id == 0 ? "disabled" : "";
$days = $func->daysInMonth($month, $year);
$dates = generateDates($year, $month, $days);




?>

<style>
    .card {
        word-wrap: normal;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Döneme Personel Ekle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $sql = $con->prepare("SELECT p.id as person,p.full_name, pt.id as pt_id FROM puantaj pt
                                        LEFT JOIN person p ON p.id = pt.person
                                        WHERE pt.company_id = ? AND pt.project_id = ? AND pt.yil = ? AND pt.ay = ? AND pt.viewonPayroll = ?");
                $sql->execute(array($company_id, $project_id, $year, $month, 0));
                $i = 1;
                $disabled = "disabled";

                while ($row = $sql->fetch(PDO::FETCH_OBJ)) {
                    $disabled = ""
                ?>
                    <table>
                        <tr>
                            <td>
                                <div class="icheck-primary d-inline ml-2">
                                    <input type="checkbox" value="" data-id="<?php echo $row->pt_id; ?>" name="namecheck<?php echo $i ?>" id="namecheck<?php echo $i ?>">
                                    <label for="namecheck<?php echo $i ?>"></label>
                                </div>
                            </td>
                            <td>
                                <span class="ml-1"><?php echo $row->full_name; ?></span>
                            </td>
                        </tr>
                    </table>
                <?php
                    $i++;
                }; ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                <button type="button" <?php echo $disabled; ?> id="add_person_toperiod" class="btn btn-primary">Personelleri Ekle</button>
            </div>
        </div>
    </div>
</div>


<div class="card card-outline card-info">
    <div class="card-header p-2">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group p-2">
                    <input type="checkbox" class="check" <?php echo $disabled; ?> id="bordro-view">
                    <span class="ml-1">Personel Bordrosunu Görsün</span>


                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group p-2">
                    <input type="checkbox" class="check" <?php echo $disabled; ?> id="donem-kapat">
                    <span class="ml-1">Puantajda İşlem Yapılmasın</span>
                </div>
            </div>


            <div class="col-md-6">
                <button type="button" id="hesapla" onclick="maas_hesapla()" class="btn btn-primary float-right"><i class="fas fa-save mr-2"></i>
                    Hesapla</button>
                <button type="button" class="btn btn-default mr-2 float-right" data-toggle="dropdown">İşlemler <i class="fa-solid fa-caret-down"></i> </button>

                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" id="excele_aktar" href="pages/bordro/toxls.php"><i class="fas fa-file-excel mr-1"></i> Bordroyu Excele Aktar</a>
                    <!-- <div class="dropdown-divider"></div> -->


                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-add mr-1"> </i> Dönemde olmayan personel ekle</a>
                    <!-- <div class="dropdown-divider"></div> -->

                </div>


            </div>

        </div>

        <style>
            .months {
                height: 360px !important;
                max-height: 360px !important;
            }
        </style>


    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="row pb-3">
            <div class="col-md-3 col-sm-12">
                <label for="company">Şirket <font color="red">*</font>
                    <span class="pointer" data-tooltip="İşlem yapacağınız şirketiniz" data-tooltip-location="right">
                        <i class="text-blue fa-solid fa-circle-info"></i>
                    </span>
                </label>
                <?php $func->companies("company", $company_id) ?>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="project">Proje <font color="red">*</font></label>
                <?php echo $func->projects("project", $company_id, $project_id) ?>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="months">Ay <font color="red">*</font></label>
                <?php echo $func->getMonthsSelect("months", $month) ?>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="year">Yıl <font color="red">*</font></label>
                <select name="year" id="year" class="select2" style="width:100%">
                    <?php
                    $current_year = date('Y');
                    for ($i = 2020; $i <= $current_year + 2; $i++) {
                        $selected = ($i == $year) ? ' selected' : ' ';
                        echo '<option ' . $selected . ' value="' . $i . '">' . $i . '</option>';
                    }

                    ?>

                </select>
            </div>

        </div>
        <?php

        //Firmaya göre kayıt yapılan personeller getirilir
        if ($project_id == null) {
            $sql = $con->prepare("SELECT * FROM sqlmaas WHERE company_id = ? AND yil = ? AND ay = ?");
            $sql->execute(array($company_id, $year, $month));
        } else {

            // SQL sorgusunu hazırlayalım
            $sql = $con->prepare("SELECT * FROM sqlmaas WHERE company_id = ? AND yil = ? AND ay = ? AND project_id REGEXP CONCAT('[[:<:]]', ?, '[[:>:]]')");
            $sql->execute(array($company_id, $year, $month, $project_id));
        }
        ?>

        <table id="example1" class="table table-bordered table-striped table-responsive table-hover">
            <thead>

                <tr>
                    <th>id</th>
                    <th>Puantaj ID</th>
                    <th>Adı Soyadı</th>
                    <th>Firma Adı</th>
                    <th>Proje Adi</th>
                    <th>Yıl</th>
                    <th>Ay</th>
                    <th class="text-center">Brüt Tutar</th>
                    <th>Kesinti</th>
                    <th>Net Ele Geçen</th>
                    <th class="text-center">Puantajda İşlem Yapılmasın</th>
                    <th class="text-center">Pers. Bord. Görsün</th>
                    <th>Hesaplama Tarihi</th>
                    <th>#</th>

                </tr>
            </thead>
            <tbody>


                <?php

                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

                ?>
                    <tr>
                        <td>
                            <?php echo $row["id"]; ?>
                        </td>
                        <td>
                            <?php echo $row["puantaj_id"]; ?>
                        </td>
                        <td>
                            <?php echo $row["full_name"]; ?>
                        </td>
                        <td>
                            <?php echo $func->getCompanyName($row["company_id"]); ?>
                        </td>
                        <?php
                        $projectNames = $func->getProjectNames($row["project_id"]);
                        ?>
                        <td class="text-nowrap" data-tooltip="<?php echo $projectNames; ?>">
                            <?php

                            echo $func->shortProjectsName($projectNames);

                            ?>
                        </td>

                        <td class="text-center">
                            <?php echo $row["yil"]; ?>
                        </td>
                        <td class="text-center">
                            <?php echo $row["ay"]; ?>
                        </td>

                        <td class="text-center text-bold">
                            <?php
                            echo tlFormat($row["toplam_maas"]); ?>
                        </td>

                        <td class="text-center">
                            <?php echo tlFormat($row["kesinti"]); ?>
                        </td>
                        <td>
                            <?php
                            $elegecen = $row["toplam_maas"] - $row["kesinti"];
                            echo tlFormat($elegecen); ?>
                        </td>
                        <td class="text-center">
                            <?php
                            if ($row["isClosed"] == 1) {
                                echo "<i class='fas fa-check text-green'></i>";
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                            if ($row["isView"] == 1) {
                                echo "<i class='fas fa-check text-green'></i>";
                            }

                            //echo $row["isView"]; 
                            ?>
                        </td>
                        <td>
                            <?php echo $row["hesaplama_tarihi"]; ?>

                        </td>
                        <td class="">

                            <i class="fa-solid fa-ellipsis list-button" data-toggle="dropdown"></i>
                            <ul class="dropdown-menu">
                            <?php 
                                $person_id =encrypt($row["id"]);

                            ;?>
                                <li class="dropdown-item edit"><i class="fa-regular fa-file-pdf dropdown-list-icon"></i></i>
                                    <a href="../pages/reports/payroll.php?project_id=<?php echo $project_id ;?>&person_id=<?php echo $person_id; ?>&month=<?php echo $month ;?>&year=<?php echo $year ;?>" target="_blank" data-title="Bordro Göster">
                                        Bordroyu Göster
                                    </a>
                                </li>

                                <li class="dropdown-item edit"><i class="fa-solid fa-scissors dropdown-list-icon"></i></i>
                                    <a href="#" onclick="RoutePagewithParams('person/wagecut','id=<?php echo ($row['id']) ?>&year=<?php echo $year ?>&month=<?php echo $month ?>')" data-title="Kesinti Ekle">
                                        Kesinti Ekle
                                    </a>
                                </li>

                                <?php
                                $params = array(
                                    "puantaj_id" => $row["puantaj_id"],
                                    "person_id" => $row["id"],
                                    "message" => "Bu kaydı silmek istediğinize emin misiniz?",
                                    "year" => $year,
                                    "month" => $month,
                                );
                                $params_json = $func->jsonEncode($params);

                                ?>

                                <li class="dropdown-item">
                                    <i class="fa-solid fa-trash-can dropdown-list-icon"></i>
                                    <a href="#" onclick="deleteRecordByAjax('bordro/main','<?php echo $params_json ?>')">Dönemden Sil!</a>
                                </li>
                            </ul>
                        </td>
                    </tr>

                <?php } ?>
            </tbody>
            <tfoot>

                <tr>
                    <th>id</th>
                    <th>Puantaj ID</th>
                    <th>Adı Soyadı</th>
                    <th>Firma Adı</th>
                    <th>Proje Adi</th>
                    <th>Yıl</th>
                    <th>Ay</th>
                    <th>Brüt Tutar</th>
                    <th>Kesinti</th>
                    <th>Net Ele Geçen</th>
                    <th class="text-center">Puantajda İşlem Yapılmasın</th>
                    <th class="text-center">Pers. Bord. Görsün</th>
                    <th>Hesaplama Tarihi</th>
                    <th>#</th>

                </tr>
            </tfoot>
        </table>


    </div><!-- /.card-body -->
</div>
<!-- /.card -->
<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/plugins/datatables/datatablescripts.php" ?>



<script>
    $("#excele_aktar").click(function() {
        var company_id = $("#company").val();
        if (company_id == "") {
            alert("Lütfen bir şirket seçiniz");
            return false;
        } else {

            window.location.href = "pages/bordro/toxls.php";
        }
    });

    $("#add_person_toperiod").click(function() {
        var puantaj_ids = [];
        var i = 0;



        $("input[type=checkbox]").each(function() {
            if (!($(this).is(":checkbox"))) {
                Swal.fire({
                    title: "Hata",
                    text: "Eklenecek personel yok",
                    icon: "error",
                    confirmButtonText: "Tamam"
                });
                return;
            }
            if ($(this).is(":checked")) {
                puantaj_ids[i] = $(this).attr("data-id");
                i++;
            }

        });

        if (puantaj_ids.length == 0) {
            swal.fire({
                title: "Hata",
                text: "Lütfen en az bir personel seçiniz",
                icon: "error",
                confirmButtonText: "Tamam"
            });
            return false;
        }
        $("#exampleModal").modal("hide");
        // $(".modal-backdrop").remove();
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                puantaj_ids: puantaj_ids,
                action: "add_person_toperiod"
            },
            success: function(data) {

                swal.fire({
                    title: "Başarılı",
                    text: "Personeller başarıyla eklendi",
                    icon: "success",
                    confirmButtonText: "Tamam"
                }).then(function() {
                    RoutePage("bordro/main");



                });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });
</script>


<script src="../../src/component.js"></script>
<script src="pages/bordro/app.js"></script>