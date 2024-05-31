<?php
require_once "../../plugins/datatables/datatable.php";
require_once "../../include/requires.php";


$company_id = isset($_GET["company_id"]) ? $_GET["company_id"] : 0;
$project_id = isset($_GET["project_id"]) ? $_GET["project_id"] : 0;
$year = isset($_GET["year"]) ? $_GET["year"] : date('Y');
$month = isset($_GET["months"]) ? $_GET["months"] : date('m');

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
                    <input type="checkbox" class="check" disabled id="bordro-view">
                    <span class="ml-1">Personel Bordrosunu Görsün</span>


                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group p-2">
                    <input type="checkbox" class="check" disabled id="donem-kapat">
                    <span class="ml-1">Puantajda İşlem Yapılmasın</span>
                </div>
            </div>
            
            <style>
                .right-btn{
                    display: flex;
                    justify-content: flex-end;
                }

            </style>
            <div class="col-md-6 right-btn">
                <button type="button" class="btn btn-default mr-2" data-toggle="dropdown">Rapor Al <i
                        class="fa-solid fa-caret-down"></i> </button>

                <div class="dropdown-menu" role="menu">
                    <a class="dropdown-item" href="#">Puantajı Excele Aktar</a>
                    <!-- <div class="dropdown-divider"></div> -->

                </div>

                <button type="button" id="" class="btn btn-primary"><i class="fas fa-save mr-2"></i>
                    Hesapla</button>
            </div>

        </div>
       



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


        <table id="example1" class="table table-bordered table-striped table-responsive-sm table-hover">
            <thead>

                <tr>
                    <th>id</th>
                    <th>Firma Adı</th>
                    <th>Parametre Adı</th>
                    <th>Parametre Türü</th>
                    <th>Başlangıç Tarihi</th>
                    <th>Bitiş Tarihi</th>
                    <th>Değer</th>
                    <th>Hesap Türü</th>
                    <th>Açıklama</th>
                    <th>Durum</th>
                    <th>Hesaplama Tarihi</th>
                    <th>#</th>

                </tr>
            </thead>
            <tbody>


                <?php

                $sql = $con->prepare("Select * from parameters WHERE account_id = ? ORDER BY id desc;");
                $sql->execute(array($account_id));


                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

                    ?>
                    <tr>
                        <td>
                            <?php echo $row["id"]; ?>
                        </td>
                        <td>
                            <?php echo $row["company_id"]; ?>
                        </td>
                        <td>
                            <?php echo $row["param_name"]; ?>
                        </td>
                        <td>
                            <?php echo $row["param_type"]; ?>
                        </td>
                        <td>
                            <?php echo $row["start_date"]; ?>
                        </td>
                        <td>
                            <?php echo $row["end_date"]; ?>
                        </td>
                        <td>
                            <?php echo $row["param_val"]; ?>
                        </td>

                        <td>
                            <?php echo $row["calc_type"]; ?>
                        </td>
                        <td>
                            <?php echo $row["description"]; ?>
                        </td>
                        <td>
                            <?php echo $row["state"]; ?>
                        </td>
                        <td>
                            <?php echo $row["created_at"]; ?>
                        </td>
                        <td class="">

                            <i class="fa-solid fa-ellipsis-vertical list-button" data-toggle="dropdown"></i>
                            <ul class="dropdown-menu">
                                <li class="dropdown-item edit"><i class="fa-solid fa-edit dropdown-list-icon"></i>
                                    <a href="#" onclick="RoutePagewithParams('params/edit','id=<?php echo $row['id'] ?>')"
                                        data-title="Parametre Güncelleme">
                                        Güncelle
                                    </a>
                                </li>

                                <!-- <?php
                                $params = array("id" => $row["id"], "message" => $row["param_name"]);
                                $params_json = $func->jsonEncode($params);
                                ?> -->

                                <li class="dropdown-item">
                                    <i class="fa-solid fa-trash-can dropdown-list-icon"></i>
                                    <a href="#"
                                        onclick="deleteRecordByAjax('params/main','<?php echo $params_json ?>')">Sil!</a>
                                </li>
                            </ul>
                        </td>
                    </tr>

                <?php } ?>
            </tbody>
            <tfoot>

                <tr>
                    <th>id</th>
                    <th>Firma Adı</th>
                    <th>Parametre Adı</th>
                    <th>Parametre Türü</th>
                    <th>Başlangıç Tarihi</th>
                    <th>Bitiş Tarihi</th>
                    <th>Değer</th>
                    <th>Hesap Türü</th>
                    <th>Açıklama</th>
                    <th>Durum</th>
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