<?php


//  echo "Kullanıcı id :" . $account_id ;
if ($account_id == ''){
    go("logout.php","");
}
if ($_POST && $_POST["method"] == "add") {
    $company_name =$func->security($_POST["company_name"]);
    $company_official = $func->security($_POST["company_official"]);
    $tax_number = $func->security($_POST["tax_number"]);
    $address = $func->security($_POST["address"]);
    $open_date = $func->security($_POST["open_date"]);
    $description = $func->security($_POST["description"]);


    try {
        $sql = $con->prepare("INSERT INTO companies SET account_id = ?, 
                                                        company_name = ? , 
                                                        company_official = ?,
                                                        tax_number = ?, 
                                                        address = ?,
                                                        open_date = ?,
                                                        description = ?");
        // $sql = $con->prepare("INSERT INTO companies SET account_id = ?, 
        //                                                 company_name = ?,
        //                                                 company_official = ?,
        //                                                 tax_number = ?,
        //                                                 address = ?,
        //                                                 open_date = ?,
        //                                                 description = ?");
        $sql->execute(array($account_id, $company_name,$company_official, $tax_number, $address, $open_date,$description));
    } catch (PDOException $ex) {
        echo "Error: " . $ex->getMessage();
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
                        <label for="company_name">Şirket Adı <span style="color:red">(*)</span></label>
                        <input id="company_name" name="company_name" type="text" class="form-control" required>
                    </div>


                    <div class="form-group">
                        <label for="company_official">Yetkili Adı</label>
                        <input id="company_official" name="company_official" type="text" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="tax_number">Vergi No</label>
                        <input id="tax_number" name="tax_number" type="text" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="address">Adresi</label>
                        <textarea type="text" id="address" name="address" class="form-control"></textarea>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.card -->
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
                        <label for="open_date">Açılış Tarihi </span></label>

                        <div class="input-group date" id="startdate" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#startdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input type="text" id="open_date" name="open_date"
                                class="form-control datetimepicker-input" data-target="#startdate"
                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy" data-mask />

                        </div>
                    </div>


                    <div class="form-group">
                        <label for="description">Açıklama</label>
                        <textarea type="text" name="description" class="form-control"></textarea>
                    </div>



                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

    </div>
    <!-- row -->
  
</form>
