<?php
require_once $_SERVER["DOCUMENT_ROOT"] ."/include/requires.php";
// echo "Kullanıcı id :" . $account_id ;
if ($account_id == '') {
    go("logout.php", "");
}

$id = isset($_GET["id"]) ? $_GET["id"] : @$_POST["id"];


if ($_POST && $_POST['method']== "update") {


    try {

        $firm_name              = @$_POST["firm_name"];
        $firm_official          = @$_POST["firm_official"];
        $city                   = @$_POST["city"];
        $town                   = @$_POST["town"];
        $email                  = @$_POST["email"];
        $account_number         = @$_POST["account_number"];
        $tax_number             = @$_POST["tax_number"];
        $address                = @$_POST["address"];
        $phone                  = @$_POST["phone"];
        $notes                  = @$_POST["notes"];

        $insq = $con->prepare("UPDATE  firms SET account_id = ? , 
                                       firm_name = ? ,firm_official = ? , 
                                       city = ? ,town = ? ,email = ? ,account_number = ? , 
                                       tax_number = ? ,address = ? ,phone = ? ,notes = ? WHERE id = ?");
                                
                                $insq->execute(array($account_id, 
                                                    $firm_name, $firm_official, 
                                                    $city, $town, $email, $account_number, 
                                                    $tax_number, $address, $phone, $notes, $id));

    } catch (PDOException $ex) {
        showAlert($ex->getMessage());
    }
}

$sql = $con->prepare("SELECT * FROM firms WHERE id = ?");
$sql->execute(array($id));
$result = $sql->fetch(PDO::FETCH_OBJ);

?>

<div class="card card-outline card-info">
    <div class="card-header p-2">

        <div class="d-flex justify-content-between">


            <ul class="nav nav-pills">
                <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Personel Listesi"
                        data-toggle="tab">Firma Listesi</a>
                </li>
           </ul>
            <?php
            $params = array(
                "method" => "update",
                "id" => $id);
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Yeni Personel"
                onclick="submitFormbyAjax('firms/edit','<?php echo $params_json ?>')"
                class="btn btn-info">Kaydet</button>
        </div>

    </div><!-- /.card-header -->
    <div class="card-body">

    <form id="myForm">
    <div class="row">

        <div class="col-md-6">
            <div class="card card-teal">
                <div class="card-header">
                    <h3 class="card-title">Genel Bilgiler</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="firm_name">Firma Adı<span style="color:red">(*)</span></label>
                        <input id="firm_name" name="firm_name" type="text" value="<?php echo $result->firm_name ;?>"
                        class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="firm_official">Yetkilisi</label>
                        <input id="firm_official" name="firm_official" type="text" value="<?php echo $result->firm_official ;?>"
                        class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="phone">Telefon <span style="color:red">(*)</span></label>
                        <input type="text" required id="phone" name="phone" value="<?php echo $result->phone ;?>"
                        class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="email">Email Adresi </label>
                        <input type="email" id="email" name="email" value="<?php echo $result->email ;?>"
                        class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="tax_number">Vergi Numarası </label>
                        <input type="text" id="tax_number" name="tax_number" value="<?php echo $result->tax_number ;?>" 
                        class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="account_number">Hesap Numarası </label>
                        <input type="text" id="account_number" name="account_number" value="<?php echo $result->account_number ;?>" 
                        class="form-control">
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->


        </div>
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Diğer Bilgiler</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="city">Şehir </label>
                        <?php echo $func->cities("city",$result->city) ?>
                    </div>

                    <div class="form-group">
                        <label for="town">İlçe</label>
                        <?php echo $func->towns("town",$result->town) ?>
                    </div>

                    <div class="form-group">
                        <label for="address">Adresi</label>
                        <textarea type="text" id="address" name="address" class="form-control"><?php echo $result->address ;?>
                        </textarea>
                    </div>

                   <div class="form-group">
                        <label for="notes">Firma Hakkında Not</label>
                        <textarea type="text" id="notes" name="notes" class="form-control"><?php echo $result->notes ;?>
                        </textarea>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
    <!-- row -->


    </form>


    </div>
</div>


<script src="../../src/component.js"></script>

<script type="text/javascript">
    $("#page-title").text("Firma Güncelle");
 
    $("#liste").click(function () {
       RoutePagewithParams("firms/main")
        $("#page-title").text("Firma Listesi");
    })


 

</script>
