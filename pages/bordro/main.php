<?php
require_once "../../plugins/datatables/datatable.php";
require_once "../../include/requires.php";



if (isset($_GET["company_id"])) {
    $company_id = $_GET["company_id"];
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
                <button type="button" class="btn btn-default mr-2 float-right" data-toggle="dropdown">Rapor Al <i class="fa-solid fa-caret-down"></i> </button>

                <div class="dropdown-menu" role="menu" id="excele_aktar">
                    <a class="dropdown-item" href="pages/bordro/toxls.php">Bordroyu Excele Aktar</a>
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
                            <?php echo tlFormat($row["toplam_maas"]); ?>
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
                                <li class="dropdown-item edit"><i class="fa-regular fa-file-pdf dropdown-list-icon"></i></i>
                                    <a href="../pages/reports/payroll.php" target="_blank" data-title="Parametre Güncelleme">
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
                                    "id" => $row["id"],
                                    "message" => "Bu kaydı silmek istediğinize emin misiniz?"
                                );
                                $params_json = $func->jsonEncode($params);
                                // 
                                ?>

                                <li class="dropdown-item">
                                    <i class="fa-solid fa-trash-can dropdown-list-icon"></i>
                                    <a href="#" onclick="deleteRecordByAjax('params/main','<?php echo $params_json ?>')">Dönemden Sil!</a>
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
include_once "../../plugins/datatables/datatablescripts.php" ?>


<script src="../../src/component.js"></script>
<script src="pages/bordro/app.js"></script>

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
</script>