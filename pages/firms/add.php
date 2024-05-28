<?php

// echo "Kullanıcı id :" . $account_id ;
if ($account_id == '') {
    go("logout.php", "");
}
// echo "hesap no :" . $account_id;

if ($_POST) {


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

        $insq = $con->prepare("INSERT INTO firms SET account_id = ? , 
                                                    firm_name = ? ,firm_official = ? , 
                                                    city = ? ,town = ? ,email = ? ,account_number = ? , 
                                                    tax_number = ? ,address = ? ,phone = ? ,notes = ?");
                                
                                $insq->execute(array($account_id, 
                                                    $firm_name, $firm_official, 
                                                    $city, $town, $email, $account_number, 
                                                    $tax_number, $address, $phone, $notes));

    } catch (PDOException $ex) {
        showAlert($ex->getMessage());
    }
}

?>
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
                        <input id="firm_name" name="firm_name" type="text" class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="company_official">Yetkilisi</label>
                        <input id="company_official" name="company_official" type="text" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="phone">Telefon <span style="color:red">(*)</span></label>
                        <input type="text" required id="phone" name="phone" class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="email">Email Adresi </label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="tax_number">Vergi Numarası </label>
                        <input type="text" id="tax_number" name="tax_number" class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="account_number">Hesap Numarası </label>
                        <input type="text" id="account_number" name="account_number" class="form-control">
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
                        <?php echo $func->cities("city","") ?>
                    </div>

                    <div class="form-group">
                        <label for="town">İlçe</span></label>
                        <select name="town" id="town" class="select2"></select>
                    </div>

                    <div class="form-group">
                        <label for="address">Adresi</label>
                        <textarea type="text" id="address" name="address" class="form-control"></textarea>
                    </div>






                    <div class="form-group">
                        <label for="notes">Firma Hakkında Not</label>
                        <textarea type="text" id="notes" name="notes" class="form-control"></textarea>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
    <!-- row -->


</form>

<script src="../../src/component.js"></script>
