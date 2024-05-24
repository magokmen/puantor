<?php
require_once "../../include/requires.php";

if ($_POST) {

    try {
        $type_name = $_POST["type_name"]; //Hareket Türü
        $type = $_POST["type"]; //Hareket Türü
        $created_at = date("Y-m-d H:i:s"); //Hareket Türü

        $sql = $con->prepare("INSERT INTO transaction_type SET type_name = ?, 
                                                               type = ?,
                                                               created_at = ?");
                                            
                                            $sql->execute(array($type_name,
                                                                $type,
                                                                $created_at));
    } catch (PDOException $ex) {
        echo "Hata : " . $ex->getMessage();
    }
}

?>
<div class="card card-outline card-info">
    <div class="card-body">


        <form id="myForm">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <label for="type_name">Hareket Adı <font color="red">*</font></label>
                    <input type="text" required class="form-control" id="type_name" name="type_name" value=""
                        placeholder="Hareket türünü yazınız">
                </div>

                <div class="col-md-6">
                    <label for="type">Hareket Tipi <font color="red">*</font></label>

                    <select class="select2" name="type" data-live-search="false" data-placeholder="Hareket Tipi seçiniz" style="width: 100%;">
                    
                        <option selected value="1">Gelir</option>
                        <option value="2">Gider</option>
                        <option value="3">Diğer</option>
                    </select>


                </div>

            </div>

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <label for="description">Tür Açıklaması <font color="red">*</font></label>
                    <input type="text" class="form-control" id="description" name="description" value=""
                        placeholder="Açıklama">
                </div>
            </div>
            <br>

            <?php
            $params = array(
                "method" => "add",
            );
            $params_json = $func->jsonEncode($params);
            ?>
            <button class="btn btn-secondary" onclick="RoutePage('kasa/main',this)" data-title="Kasa"
                type="button">Listeye
                Dön</button>

           


        </form>
    </div>
</div>
<script>
    $('.select2').select2({
        minimumResultsForSearch: Infinity
    })

</script>