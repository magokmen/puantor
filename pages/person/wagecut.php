<?php require_once "../../include/requires.php";
$id = $_GET["id"];
?>


<div class="card card-outline card-info">
    <div class="card-header p-2">

        <div class="d-flex justify-content-between">


            <ul class="nav nav-pills">
                <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Bordro" data-toggle="tab">Bordroya Dön</a>
                </li>
            </ul>
            <?php
            $params = array(
                "method" => "cut-add",
                "id" => $id
            );
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Yeni Personel" onclick="submitFormbyAjax('person/wagecut','<?php echo $params_json ?>')" class="btn btn-info">Kaydet</button>
        </div>

    </div><!-- /.card-header -->
    <div class="card-body">

        <form id="myForm">

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="company">Firması <span style="color:red">(*)</span></label>
                    <?php $func->companies("companies", ""); ?>
                </div>


                <div class="form-group col-md-6">
                    <label for="projects">Projesi <span style="color:red">(*)</span></label>
                    <select class="select2" id="projects" name="projects" data-placeholder="Proje Seçiniz" data-dropdown-css-class="select2" style="width: 100%;">

                    </select>

                </div>
            </div>

            <div class="row">

                <div class="form-group col-md-6">
                    <label for="person">Adı Soyadı</label>
                    <input id="person" name="person" type="text" class="form-control" value="">
                </div>

                <div class="form-group col-md-6">
                    <label for="cut_type">Kesinti Türü</label>
                    <select class="select2" id="cut_type" name="cut_type" class="select2" style="width: 100%;">
                        <option value="-1">Tür Seçiniz</option>
                        <option value="1">Devamsızlık Kesintisi</option>
                        <option value="2">BES Kesintisi</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-3">
                    <label for="cut_amount">Kesinti Yılı</label>
                    <?php echo $func->getYearSelect("years"); ?>
                </div>
                <div class="form-group col-md-3">
                    <label for="cut_amount">Kesinti Ayı</label>
                    <?php echo $func->getMonthsSelect("months") ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="cut_amount">Kesinti Tutarı</label>
                    <input id="cut_amount" name="cut_amount" type="text" class="form-control" value="">
                </div>
            </div>  

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="address">Açıklama</label>
                    <textarea type="text" id="address" name="address" class="form-control"></textarea>
                </div>

            </div>

        </form>

    </div>

</div>


<script src="../../src/component.js"></script>

<script>
    $("#liste").click(function () {
       RoutePagewithParams("bordro/main")
        $("#page-title").text("Bordro");
    })
</script>