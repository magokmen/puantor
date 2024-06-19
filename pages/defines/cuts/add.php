<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/requires.php";

//  echo "Kullanıcı id :" . $account_id ;
if ($account_id == '') {
    go("logout.php", "");

}


if ($_POST && $_POST["method"] = "add") {

         try {
        // Get form data
        $con->beginTransaction();
        $company_id = $_POST['company'];
        $type_name = $_POST['type_name'];
        $description = $_POST['description'];

        // Insert data into cut_types table
        $stmt = $con->prepare("INSERT INTO cut_types (account_id,company_id, type_name, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$account_id, $company_id, $type_name, $description]);

        // Check if the insertion was successful
        if ($stmt->rowCount() > 0) {
            $res = array(
                "status" => 200,
                "message" => "İşlem başarıyla gerçekleşti!",
                "page" => "defines/cuts/main",
                "pTitle" => "Kesinti Türü Listesi"
            );
            $con->commit();
        } else {
            $res = array(
                "status" => 500,
                "message" => "Veritabanı hatası: Ekleme işlemi başarısız oldu."
            );
           
        }

        } catch (PDOException $ex) {
            $res = array(
                "status" => 500,
                "message" => "Veritabanı hatası: " . $ex->getMessage()
            );
            $con->rollBack();

        }

    echo json_encode($res); // JSON yanıtı döndürün
    return;
}


?>

<style>
 
    .container {
        padding: 0px !important;
        max-width: 100% !important;
    }
</style>

<form id="myForm">
    <div class="container">


        <div class="row">
            <div class="form-group col-md-6">
                <label for="company">Şirket Adı</label> <span style="color:red">(*) Türün geçerli
                    olacağı
                    şirketiniz</span>
                <?php echo $func->companies("company", ""); ?>
            </div>
            <div class="form-group col-md-6">
                <label for="type_name">Kesinti Türü Adı</label><span style="color:red"> (*)</span>
                <input required id="type_name" name="type_name" type="text" class="form-control">
            </div>
        </div>
        


        <div class="row">
            <div class="form-group col-md-12">
                <label for="description">Açıklama</label>
                <textarea type="text" name="description" class="form-control"></textarea>
            </div>

        </div>
    </div>
</form>

