<?php
require_once "../../include/requires.php";
// $id = isset($_GET["id"]) ?  $_GET["id"] : 0;
// echo "id : " . $id;


?>


<div class="card card-outline card-info">
    <div class="card-body">
        <div class="row">

            <?php
            echo "sdafsdf";

            ?>
        </div>

        <!-- /.card -->

        <button class="btn btn-secondary" id="returnlist" data-title="Hesap Listesi" type="button">Listeye
            Dön</button>

        <?php
        $params = array("method" => "add");
        $params_json = $func->jsonEncode($params);
        ?>

        <button type="button" id="" data-title="Yeni Personel"
            onclick="submitFormbyAjax('person/main','<?php echo $params_json ?>')"
            class="btn btn-primary float-right">Kaydet</button>
    </div>
</div>

<script>


    $(document).ready(function () {

        $("#returnlist").click(function () {
            var title = $(this).data("title");

            RoutePagewithParams("accounts/list");
            $("#page-title").text(title);

        })



    })
</script>