<?php require_once "../../include/requires.php";
$id = isset($_GET["id"]) ? $_GET["id"] : $_POST["id"];

if ($_POST && $_POST['method'] === 'add') {
    try {
        //Get form data
   
        $company_id = $func->security($_POST['companies']);
        $project_id = $func->security($_POST['projects']);
        $person_id = $func->security($_POST['person_id']);
        $cutType = $func->security($_POST['cut_type']);
        $year = $func->security($_POST['year']);
        $month = $func->security($_POST['month']);
        $cutAmount = $func->security($_POST['cut_amount']);
        $description = $func->security($_POST['description']);

        // Prepare and execute the SQL statement
        $stmt = $con->prepare("INSERT INTO wagecuts (account_id, company_id , project_id, person_id, cut_type, year, month, cut_amount, description) 
                                VALUES (?, ?, ?, ?,?, ?, ?, ?, ?)");
        $stmt->execute([$account_id,$company_id, $project_id, $person_id, $cutType, $year, $month, $cutAmount, $description]);

        // Return JSON response
        $res = array(
            'status' => 200,
            'message' => 'Wage cut added successfully',
            "page" =>"bordro/main",
            "pTitle" => "Bordro"
        );
        echo json_encode($res);
        return ;

    } catch (PDOException $e) {
        // Handle the exception
        echo json_encode(array(
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ));
        return ;
    }
}

$sql = $con->prepare("SELECT * FROM person WHERE id = ?");
$sql->execute(array($id));
$result = $sql->fetch(PDO::FETCH_OBJ);

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
                "method" => "add",
                "id" => $id,
                
            );
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Bordro" onclick="submitFormReturnJson('person/wagecut','<?php echo $params_json ?>')" class="btn btn-info">Kaydet</button>
        </div>

    </div><!-- /.card-header -->
    <div class="card-body">

        <form id="myForm">

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="company">Firması <span style="color:red">(*)</span></label>
                    <?php $func->companies("companies", $result->company_id); ?>
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

                    <input id="person_id" name="person_id" type="hidden" class="form-control" value="<?php echo $id ;?>">
                    <input id="person" readonly disabled name="person" type="text" class="form-control" value="<?php echo $result->full_name ;?>">
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
                    <?php echo $func->getYearSelect("year"); ?>
                </div>
                <div class="form-group col-md-3">
                    <label for="cut_amount">Kesinti Ayı</label>
                    <?php echo $func->getMonthsSelect("month") ?>
                </div>
                <div class="form-group col-md-6">
                    <label for="cut_amount">Kesinti Tutarı</label>
                    <input id="cut_amount" name="cut_amount" type="text" class="form-control" value="">
                </div>
            </div>  

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="description">Açıklama</label>
                    <textarea type="text" id="description" name="description" class="form-control"></textarea>
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