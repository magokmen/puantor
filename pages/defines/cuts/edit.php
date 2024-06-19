<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/requires.php";
if ($account_id == '') {
    go("logout.php", "");
}

$id = isset($_GET["id"]) ? $_GET["id"] : @$_POST["id"];


if ($_POST && $_POST["method"] = "update") {

    try {
        // Get form data
        $con->beginTransaction();
        $company_id = $_POST['company'];
        $type_name = $_POST['type_name'];
        $description = $_POST['description'];

        // Insert data into cut_types table
        $stmt = $con->prepare("UPDATE cut_types SET account_id = ?, company_id = ?, type_name = ?, description = ? WHERE id = ?");
        $stmt->execute([$account_id, $company_id, $type_name, $description, $id]);

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

$sql = $con->prepare("SELECT * FROM cut_types WHERE id = ?");
$sql->execute(array($id));
$result = $sql->fetch(PDO::FETCH_OBJ);


?>

<div class="card card-outline card-info">
    <div class="card-header p-2">

        <div class="d-flex justify-content-between">


            <ul class="nav nav-pills">
                <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Kesinti Türü Listesi" data-toggle="tab">Kesinti Türü Listesi</a>
                </li>
            </ul>
            <?php
            $params = array(
                "method" => "update",
                "id" => $id
            );
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Yeni Personel" onclick="submitFormReturnJson('defines/cuts/edit','<?php echo $params_json ?>')" class="btn btn-info">Kaydet</button>
        </div>

    </div><!-- /.card-header -->
    <div class="card-body">

        <form id="myForm">
            <div class="container">


                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="company">Şirket Adı</label> <span style="color:red">(*) Türün geçerli
                            olacağı
                            şirketiniz</span>
                        <?php echo $func->companies("company", $result->company_id); ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="type_name">Kesinti Türü Adı</label><span style="color:red"> (*)</span>
                        <input required id="type_name" name="type_name" type="text" class="form-control" value="<?php echo $result->type_name ;?>">
                    </div>
                </div>



                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="description">Açıklama</label>
                        <textarea type="text" name="description" class="form-control"><?php echo $result->type_name ;?></textarea>
                    </div>

                </div>
            </div>
        </form>

    </div>
</div>


<script src="../../src/component.js"></script>

<script type="text/javascript">
    $("#page-title").text("Kesinti Türü Güncelle");
    $("#liste").click(function() {
        RoutePagewithParams("defines/cuts/main")
        $("#page-title").text("Kesinti Türü Listesi");
    })
</script>