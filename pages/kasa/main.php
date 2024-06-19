<?php


require_once $_SERVER["DOCUMENT_ROOT"] . "/include/requires.php";

if ($_POST && $_POST['action'] == "add-transaction") {
   

    $type = $_POST["type"];
    $vault_id = $_POST["vault_id"];
    $vault_date = $_POST["vault_date"];
    $amount = $_POST["amount"];
    $transaction_description = $_POST["transaction_description"];

    try {
        $sql = $con->prepare("INSERT INTO cases_transactions SET type_id = ? , sub_type = ?, 
                                                           vault_id= ?, date = ?, 
                                                           amount = ?, description =  ?");
                                                
        $sql->execute(array($type, 2, $vault_id, $vault_date, $amount, $transaction_description));

        $res = array(
            "status" => 200,
            "message" => "Başarılı",
    
        );
    } catch (PDOException $ex) {
        $res = array(
            "status" => 400,
            "message" => "Hata : " . $ex->getMessage()
        );
    }
    
    echo json_encode($res);
    return false;
}


if ($_POST && $_POST['action'] == "delete-transaction") {
    $transaction_id = $_POST["id"];

    try {
        $sql = $con->prepare("DELETE FROM cases_transactions WHERE id = ?");
        $sql->execute(array($transaction_id));
        $res = array(
            "status" => 200,
            "message" => "Başarılı",
        );
    } catch (PDOException $ex) {
        $res = array(
            "status" => 400,
            "message" => "Hata : " . $ex->getMessage(),
        );
    }

    echo json_encode($res);
    return false;
}


if ($_POST && $_POST['action'] == "delete-case") {
     $case_id = $_POST["id"];
    try {
        $sql = $con->prepare("DELETE FROM cases WHERE id = ?");
        $sql->execute(array($case_id));
        $res = array(
            "status" => 200,
            "message" => "Başarılı",
        );
    } catch (PDOException $ex) {
        $res = array(
            "status" => 400,
            "message" => "Hata : " . $ex->getMessage(),
        );
    }

    echo json_encode($res);
    return false;
}
?>


<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Kasa Hareketi Ekle</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
                <form id="myForm" class="form-horizontal">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="vault_date" class="col-sm-3 col-form-label">Tarih : <span style="color:red">(*)</span></label>
                            <div class="col-sm-9">
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input required type="text" id="vault_date" name="vault_date" class="form-control datetimepicker-input" data-target="#reservationdate" data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy" data-mask />
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="amount" class="col-sm-3 form-label">Tutar : <span style="color:red">(*)</span></label>
                            <div class="col-sm-9">
                                <input required type="number" class="form-control" id="amount" name="amount" value="" placeholder="Tutar">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Açıklama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="transaction_description" name="transaction_description" value="" placeholder="Açıklama">
                            </div>
                        </div>

                    </div>
                </form>

                <?php
                $param = array(
                    "method" => "transaction",
                    "type" => 1,

                );
                $params = $func->jsonEncode($param);
                ?>


            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                <button type="button" class="btn btn-primary closeModal">Kaydet</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<style>
    /* .hover-menu ul li {
        padding: 10px 0; 
         margin: 5px;
    } */

    .hover-menu ul li:hover {
        background-color: #eee;
        border-radius: 6px;
        cursor: pointer;

    }

    .hover-menu .nav-item {
        padding: 7px !important;
        margin: 2px !important;

    }

    .card-body .hover-menu {
        padding: 5px !important;

    }

    .hover-menu ul li:active {
        background-color: #ccc;
        transition: background-color 0.4s ease;
        /* Geçiş efekti */
    }

    .kasa ul li.clicked {
        background: #B7B7B7;
        /* fallback for old browsers */
        /* background: -webkit-linear-gradient(to bottom, #46C6DC, #24C6DC);
        /* Chrome 10-25, Safari 5.1-6 */
        /* background: linear-gradient(to bottom, #46C6DC, #24C6DC); */
        /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        color: white;
        border-radius: 6px;

    }

    .avatar {

        color: white;
        opacity: 1;
        content: attr(data-initials);
        font-weight: bold;
        border-radius: 50%;
        vertical-align: middle;
        margin-right: 0.4em;
        width: 35px;
        height: 35px;
        line-height: 35px;
        text-align: center;
        float: left;
    }

    .avatar-big {
        width: 70px;
        height: 70px;
        line-height: 70px;
        color: #222;
    }
</style>

<div class="card">

    <div class="card-body">
        <input type="hidden" id="vault_id" name="vault_id" value="0">

        <div class="row">

            <div class="col-md-3">

                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <h3 class="card-title">Kasalar</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body hover-menu kasa scroll-smooth" style="max-height: 300px; overflow-y: scroll;">
                        <!-- <div class="card-body hover-menu kasa scroll-smooth"> -->
                        <ul class="nav flex-column">
                            <?php

                            $sql = $con->prepare("SELECT c.*,a.company_name FROM `cases` c  
                                                  LEFT JOIN accounts a ON a.id = c.account_id 
                                                  WHERE c.account_id= ?");
                            $sql->execute(array($account_id));

                            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                <li class="nav-item" data-params="<?php echo $row["id"]; ?>">
                                    <div class="user-block kasa-item" data-params="<?php echo $row["id"]; ?>">
                                        <span class="avatar" style="background-color: #055bcc;"><?php echo getInitials($row["case_name"]); ?></span>
                                        <span class="username">
                                            <?php echo $row["case_name"] ?>
                                        </span>
                                        <span class="description"><?php echo $row["company_name"]; ?></span>

                                    </div>
                                    <i class="fa-solid fa-ellipsis-vertical list-button float-right" data-toggle="dropdown"></i>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-item edit-case"><i class="fa-solid fa-edit dropdown-list-icon"></i>
                                            <a href="#" onclick="RoutePagewithParams('kasa/edit', '')" data-title="Kasa Güncelleme">Güncelle</a>
                                        </li>
                                        <li class="dropdown-item delete-case" data-id="<?php echo $row["id"]; ?>"><i class="fa-solid fa-trash dropdown-list-icon"></i>
                                            <a href="#" >Sil</a>
                                        </li>
                                    </ul>
                                </li>

                            <?php

                            }

                            ?>
                        </ul>



                    </div>
                    <div class="card-footer">
                        <?php if (permtrue("kasaEkle")) : ?>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a href="#" onclick="RoutePage('kasa/add', this)" class="nav-link">
                                        Kasa Ekle <span class="float-right badge bg-danger">Yeni</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            </ul>
                    </div>
                    <!-- /.card-body -->
                </div>




                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Hareket Ekle</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body hover-menu">
                        <ul class="nav flex-column">
                            <?php if (permtrue("kasaGelirEkle")) : ?>
                                <li class="nav-item glr" data-toggle="modal" data-target="#modal-default">
                                    <div class="user-block">
                                        <span class="avatar" style="background-color: #055bcc;">Glr</span>
                                        <span class="username">
                                            Gelir Ekle
                                        </span>
                                        <span class="description">Seçili Kasaya Gelir Ekle</span>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <?php if (permtrue("kasaGiderEkle")) : ?>
                                <li class="nav-item">
                                    <div class="user-block">
                                        <span class="avatar" style="background-color: #FE7A36;">Gdr</span>
                                        <span class="username">
                                            Gider Ekle

                                        </span>
                                        <span class="description">Seçili Kasaya Gider Ekle</span>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <?php if (permtrue("kasaProjeÖdemeAl")) : ?>
                                <li class="nav-item">
                                    <div class="user-block">
                                        <span class="avatar" style="background-color: #81689D;">Pr</span>
                                        <span class="username">
                                            Projeden Ödeme Al
                                        </span>
                                        <span class="description">Projeden Ödeme Al</span>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <?php if (permtrue("kasaPersonelÖdeme")) : ?>
                                <li class="nav-item">
                                    <div class="user-block">
                                        <span class="avatar" style="background-color: #3887BE;">Pö</span>
                                        <span class="username">
                                            Personel Ödemesi Yap
                                        </span>
                                        <span class="description">Personel Ödemesi yap</span>
                                    </div>
                                </li>
                            <?php endif; ?>
                            <!-- <li class="nav-item">
                                <a href="#" onclick="RoutePage('kasa/hareket-turu', this)" class="nav-link">
                                    Hareket Türü Ekle <span class="float-right badge bg-danger">Yeni</span>
                                </a>
                            </li> -->
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.widget-user -->
            </div>
            <div class="col-md-9">
                <div class="card card-outline card-info">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <?php if (permtrue("kasaHareketleri")) : ?>

                                <li class="nav-item"><a class="tabMenu active nav-link" id="liste" href="#list" data-title="Kasa" data-toggle="tab">Kasa Hareketleri</a>
                                </li>
                            <?php endif; ?>
                            <?php if (permtrue("kasaÖzeti")) : ?>

                                <li class="nav-item"><a class="tabMenu nav-link" id="yeni" href="#add" data-title="Kasa" data-toggle="tab">Özet</a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">

                            <!-- /.tab-pane -->


                            <div class="tab-pane fade" id="add">

                                <?php include "ozet.php" ?>

                            </div>
                            <div class="tab-pane active show fade" id="list">

                                <?php include "list.php" ?>
                            </div>

                        </div>

                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->


            </div>

        </div>
    </div>

</div>
<script src="pages/kasa/app.js"></script>