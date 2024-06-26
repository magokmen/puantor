<?php require_once "../../include/requires.php";


$id = isset($_GET["id"]) ? ($_GET["id"]) : $_POST["id"];
$year = isset($_GET["year"]) ? $_GET["year"] : $_POST["year"];
$month = isset($_GET["month"]) ? $_GET["month"] : $_POST["month"];

if ($_POST && $_POST['method'] === 'add') {

    if (empty($_POST['cut_type']) || empty($_POST['cut_amount']) || empty($_POST['calc_type'])) {
        echo json_encode(array(
            'success' => false,
            'message' => 'Lütfen tüm alanları doldurunuz.'
        ));
        return;
    }
    try {
        $con->beginTransaction();
        $cutType = $func->security($_POST['cut_type']);
        $cutAmount = $func->security($_POST['cut_amount']);
        $calc_type = $func->security($_POST['calc_type']);
        $description = $func->security($_POST['description']);

        // Prepare and execute the SQL statement
        $stmt = $con->prepare("INSERT INTO wagecuts ( person_id, cut_type, year, month, cut_amount, calc_type ,description) 
                                    VALUES (?, ?, ?, ?, ?, ?,?)");
        $stmt->execute([$id, $cutType, $year, $month, $cutAmount, $calc_type, $description]);
        $con->commit();
        logAction("INSERT", "wagecuts","", $_POST);
        
        
        // Return JSON response
        $res = array(
            'status' => 200,
            'message' => 'Kesinti başarıyla eklendi.',
            "page" => "bordro/main",
            "pTitle" => "Bordro"
        );
        echo json_encode($res);
        return;
    } catch (PDOException $e) {
        // Handle the exception
        $con->rollBack();
        echo json_encode(array(
            'success' => false,
            'message' => 'Bir hata oluştu: ' . $e->getMessage()
        ));
        return;
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
                "year" => $year,
                "month" => $month,

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
                    <label for="company">Şirket <span style="color:red">(*)</span></label><small>İşlem yapılacak şirket</small>
                    <?php $func->companies("companies", $result->company_id, "disabled", "readonly"); ?>
                </div>


                <div class="form-group col-md-6">
                    <label for="projects">Projesi <span style="color:red">(*)</span></label><small>Personelin çalıştığı proje veya şantiye</small>

                    <input type="text" disabled readonly class="form-control" value="<?php echo $func->getProjectNames($result->project_id); ?>" />


                </div>
            </div>

            <div class="row">

                <div class="form-group col-md-6">
                    <label for="person">Adı Soyadı</label>

                    <input id="person_id" name="person_id" type="hidden" class="form-control" value="<?php echo $id; ?>">
                    <input id="person" readonly disabled name="person" type="text" class="form-control" value="<?php echo $result->full_name; ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="cut_amount">Kesinti Yılı</label>
                    <?php echo $func->getYearSelect("year", $year, "disabled", "readonly"); ?>
                </div>
                <div class="form-group col-md-3">
                    <label for="cut_amount">Kesinti Ayı</label>
                    <?php echo $func->getMonthsSelect("month", $month, "disabled", "readonly") ?>
                </div>

            </div>

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="cut_type">Kesinti Türü</label>
                    <select required class="select2" id="cut_type" name="cut_type" class="select2" style="width: 100%;">
                        <option value="">Tür Seçiniz</option>
                        <option value="1">Devamsızlık Kesintisi</option>
                        <option value="2">BES Kesintisi</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="cut_amount">Kesinti Tutarı</label>
                    <input required id="cut_amount" name="cut_amount" type="text" class="form-control" value="">
                </div>
                <div class="form-group col-md-3">
                    <label for="calc_type">Hesaplama Türü</label>
                    <select required name="calc_type" id="calc_type" class="select2 form-control">
                        <option value="">Tür Seçiniz</option>
                        <option value="1">TL</option>
                        <option value="2">Ücretin Yüzdesi</option>
                    </select>
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
    $("#liste").click(function() {
        RoutePagewithParams("bordro/main")
        $("#page-title").text("Bordro");
    })
</script>