<?php
require_once "../../include/requires.php";

if ($_POST && $_POST['method'] === 'add') {

    try {
        //Get form data
        $con->beginTransaction();
        $company_id = $_POST['companies'];
        $startBudget = $_POST['start_budget'];
        $caseName = $_POST['case_name'];
        $bankName = $_POST['bank_name'];
        $branchName = $_POST['branch_name'];
        $description = $_POST['description'];

        // Insert data into kasalar table
        $stmt = $con->prepare("INSERT INTO cases (account_id, company_id, start_budget, case_name, bank_name, branch_name, description) VALUES (?,?, ?, ?, ?, ?, ?)");
        $stmt->execute([$account_id, $company_id, $startBudget, $caseName, $bankName, $branchName, $description]);
        $con->commit();
        //Check if the insertion was successful
        echo json_encode([
            "status" => 200, 
            "message" => "Kayıt başarıyla eklendi.",
            "page" => "kasa/main",
            "pTitle" => "Kasa"]);
        return ;
       
    } catch (PDOException $ex) {
        echo json_encode(["status" => "error", "message" =>  $ex->getMessage() ]);
       
    }
}

?>
<div class="card card-outline card-info">
    <div class="card-body">


        <form id="myForm">
            <div class="row mb-2">
                <div class="col-md-6 col-sm-12">
                    <label for="companies">Firması<font color="red">*</font></label>
                    <?php echo $func->companies("companies", ""); ?>
                </div>


                <div class="col-md-6 col-sm-12">
                    <label for="start_budget">Açılış Bütçesi<font color="red">*</font></label>
                    <input type="text" name="start_budget" class="form-control">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6 col-sm-12">
                    <label for="case_name">Kasa Adı <font color="red">*</font></label>
                    <input type="text" required class="form-control" id="case_name" name="case_name" value="" placeholder="Kasa adını yazınız">
                </div>

                <div class="col-md-3">
                    <label for="bank_name">Bankası <font color="red">*</font></label>
                    <select class="select2" name="bank_name" data-live-search="false" data-placeholder="Bankasını seçiniz" style="width: 100%;">
                        <option selected value="1">Akbank</option>
                        <option value="2">Garanti Bankası</option>
                        <option value="3">Ziraat Bankası</option>
                    </select>
                </div>
                <div class="col-md-3 col-sm-12">
                    <label for="branch_name">Şube Adı <font color="red">*</font></label>
                    <input type="text" required class="form-control" id="branch_name" name="branch_name" value="" placeholder="Şube adını yazınız">
                </div>

            </div>

            <div class="row mb-2">
                <div class="col-md-12 col-sm-12">
                    <label for="description">Tür Açıklaması <font color="red">*</font></label>
                    <input type="text" class="form-control" id="description" name="description" value="" placeholder="Açıklama">
                </div>
            </div>
            <br>

       
            <?php
            $params = array(
                "method" => "add",
               
            
            );
            $params_json = $func->jsonEncode($params);
            ?>
            <button class="btn btn-secondary" onclick="RoutePage('kasa/main',this)" data-title="Kasa" type="button">Listeye
                Dön</button>

            <button class="btn btn-info float-right" onclick="submitFormReturnJson('kasa/add','<?php echo $params_json ?>')" type="button"><i class="fas fa-save mr-2"></i> Kaydet</button>



        </form>
    </div>
</div>
<script>
    $('.select2').select2({
        minimumResultsForSearch: Infinity
    })
</script>