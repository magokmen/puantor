<?php

// echo "Kullanıcı id :" . $account_id ;

$type = isset($_GET["type"]) ? $_GET["type"] : $_POST["type"];
if ($_POST && $_POST["method"] == "add") {


    $company_id = $_POST["companies"];
    $firm_id = $_POST["firms"];
    $project_name = $_POST["project_name"];
    $budget = $_POST["budget"];
    $city = $_POST["city"];
    $town = $_POST["town"];
    $address = $_POST["address"];
    $email = $_POST["email"];
    $account_number = $_POST["account_number"];
    $notes = $_POST["notes"];
    $start_date = $_POST["start_date"];
    $file = $_FILES["project_file"]["name"];
    $creator = sesset("id");

    try {


        if (isset($file)) {
            $uploadDir = '../../files/'; // Değiştirilmesi gereken dizin
            $uploadPath = $uploadDir . basename($file);
            // Dosyayı belirtilen dizine taşı,
            if (move_uploaded_file($_FILES["project_file"]["tmp_name"], $uploadPath)) {

            }
        }
        $insq = $con->prepare("INSERT INTO projects SET type = ? ,account_id = ?, company_id = ? , firm_id = ?,
                project_name = ? , 
                budget = ? , 
                city = ? , 
                town = ? , 
                address = ? , 
                email = ? , 
                account_number = ? , 
                notes = ? , 
                start_date = ? ,
                file_name = ?,
                creator = ? ");
        $insq->execute(
            array(
                $type,
                $account_id,
                $company_id,
                $firm_id,
                $project_name,
                $budget,
                $city,
                $town,
                $address,
                $email,
                $account_number,
                $notes,
                $start_date,
                $file,
                $creator
            )
        );



    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }
}
;


?>

<form id="myForm">
    <div class="row">

        <div class="col-md-6">
            <div class="card card-teal">
                <div class="card-header">
                    <h3 class="card-title">Proje Bilgileri</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <input type="text" name="type" readonly disabled value="<?php echo $type; ?>">
                    <div class="form-group">
                        <label for="companies">Şirket <span style="color:red">(*)</span><small> İşlem yapacağınız
                                şirketinizi seçiniz.</small></label>
                        <?php echo $func->companies("companies", "") ?>
                    </div>

                    <div class="form-group">
                        <label for="companies">Firma Adı <span style="color:red">(*)</span></label>
                        <?php echo $func->firms("firms", "") ?>
                    </div>

                    <div class="form-group">
                        <label for="project_name">Proje Adı</label>
                        <input id="project_name" name="project_name" type="text" class="form-control">
                    </div>



                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <div class="card card-danger">
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
                        <label for="start_date">Proje Başlama Tarihi <span style="color:red">(*)</span></label>

                        <div class="input-group date" id="startdate" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#startdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input type="text" id="start_date" name="start_date"
                                class="form-control datetimepicker-input" data-target="#startdate"
                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy" data-mask />

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="budget">Açılış Bütçesi</label>
                        <input id="budget" name="budget" type="text" class="form-control">
                    </div>



                    <div class="form-group">
                        <label for="project_file">Sözleşmesi</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="project_file" id="project_file">
                                <label class="custom-file-label" for="project_file">Dosya Seçin</label>
                            </div>
                        </div>
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
                        <label for="city">Şehir<span style="color:red">(*)</span> </label>
                        <?php echo $func->cities("city", ""); ?>
                    </div>

                    <div class="form-group">
                        <label for="town">İlçe</label>
                        <select name="town" id="town" class="select2"></select>
                    </div>

                    <div class="form-group">
                        <label for="address">Adresi</label>
                        <textarea type="text" id="address" name="address" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Adresi </label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="account_number">Hesap Numarası </label>
                        <input type="text" id="account_number" name="account_number" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="notes">Proje Hakkında Not</label>
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