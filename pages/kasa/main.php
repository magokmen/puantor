<?php
require_once "../../include/requires.php";

if ($_POST) {
    $type = 2;
    $vault_id = $_POST["vault_id"];
    $vault_date = $_POST["vault_date"];
    $amount = $_POST["amount"];
    $transaction_description = $_POST["transaction_description"];

    try {
        $sql = $con->prepare("INSERT INTO transactions SET sub_type = ?, 
                                                           vault_id= ?, date = ?, 
                                                           amount = ?, description =  ?");
        $sql->execute(array($type, $vault_id, $vault_date, $amount, $transaction_description));
    } catch (PDOException $ex) {
        echo "Hata : " . $ex->getMessage();
    }
    $res = array(
        "status" => 200,
        "message" => "Başarılı",

    );
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
                            <label for="vault_date" class="col-sm-3 col-form-label">Tarih : <span
                                    style="color:red">(*)</span></label>
                            <div class="col-sm-9">
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input required type="text" id="vault_date" name="vault_date"
                                        class="form-control datetimepicker-input" data-target="#reservationdate"
                                        data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy"
                                        data-mask />
                                    <div class="input-group-append" data-target="#reservationdate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="amount" class="col-sm-3 form-label">Tutar : <span
                                    style="color:red">(*)</span></label>
                            <div class="col-sm-9">
                                <input required type="number" class="form-control" id="amount" name="amount" value=""
                                    placeholder="Tutar">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputPassword3" class="col-sm-3 col-form-label">Açıklama</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="transaction_description"
                                    name="transaction_description" value="" placeholder="Açıklama">
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
    .hover-menu ul li {
        padding: 10px 0;
        margin: 5px;
    }

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
        background: #24C6DC;
        /* fallback for old browsers */
        background: -webkit-linear-gradient(to bottom, #46C6DC, #24C6DC);
        /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to bottom, #46C6DC, #24C6DC);
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
                        <h3 class="card-title">Hareket Ekle</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body hover-menu kasa">
                        <ul class="nav flex-column">
                            <?php

                            $sql = $con->prepare("SELECT k.*,a.company_name FROM `kasalar` k  
                                                  LEFT JOIN accounts a ON a.id = k.account_id 
                                                  WHERE k.account_id= ?");
                            $sql->execute(array($account_id));

                            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                                ?>
                                <li class="nav-item" data-params="<?php echo $row["id"]; ?>">
                                    <div class="user-block">
                                        <span class="avatar" style="background-color: #055bcc;">ZK</span>
                                        <span class="username">
                                            <?php echo $row["account_name"] ?>
                                        </span>
                                        <span class="description"><?php echo $row["company_name"] ;?></span>
                                    </div>
                                </li>

                                <?php

                            }

                            ?>

                            <li class="nav-item">
                                <a href="#" onclick="RoutePage('kasa/add', this)" class="nav-link">
                                    Kasa Ekle <span class="float-right badge bg-danger">Yeni</span>
                                </a>
                            </li>
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
                            <li class="nav-item" data-toggle="modal" data-target="#modal-default">
                                <div class="user-block">
                                    <span class="avatar" style="background-color: #055bcc;">Glr</span>
                                    <span class="username">
                                        Gelir Ekle
                                    </span>
                                    <span class="description">Seçili Kasaya Gelir Ekle</span>
                                </div>
                            </li>
                            <li class="nav-item">
                                <div class="user-block">
                                    <span class="avatar" style="background-color: #FE7A36;">Gdr</span>
                                    <span class="username">
                                        Gider Ekle

                                    </span>
                                    <span class="description">Seçili Kasaya Gider Ekle</span>
                                </div>
                            </li>

                            <li class="nav-item">
                                <div class="user-block">
                                    <span class="avatar" style="background-color: #81689D;">Pr</span>
                                    <span class="username">
                                        Projeden Ödeme Al

                                    </span>
                                    <span class="description">Projeden Ödeme Al</span>
                                </div>
                            </li>

                            <li class="nav-item">
                                <div class="user-block">
                                    <span class="avatar" style="background-color: #3887BE;">Pö</span>
                                    <span class="username">
                                        Personel Ödemesi Yap

                                    </span>
                                    <span class="description">Personel Ödemesi yap</span>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="#" onclick="RoutePage('kasa/hareket-turu', this)" class="nav-link">
                                    Hareket Türü Ekle <span class="float-right badge bg-danger">Yeni</span>
                                </a>
                            </li>
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
                            <li class="nav-item"><a class="tabMenu active nav-link" id="liste" href="#list"
                                    data-title="Kasa" data-toggle="tab">Kasa Hareketleri</a>
                            </li>
                            <li class="nav-item"><a class="tabMenu nav-link" id="yeni" href="#add" data-title="Kasa"
                                    data-toggle="tab">Özet</a></li>

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

<script>
    $(function () {

        var pagetitle = $("#page-title").text();
        if (pagetitle == "Kasa") {
            $("#liste").tab("show");
        }

        if (pagetitle == "Özet") {
            alert("")
            $("#kasaozet").tab("show");
        }
    })

    $(function () {
        $(".tabMenu").click(function () {
            var navLinkText = $(this).data("title");
            $("#page-title").text(navLinkText);
            setActiveMenu(this);
        });
    });

    $('[data-mask]').inputmask('dd.mm.yyyy')
    $('#reservationdate').datetimepicker({
        format: 'DD.MM.YYYY',
        locale: 'tr'

    });

    $(".closeModal").click(function () {
        submitFormByModal();

    })
    function submitFormByModal() {
        var vault_id = $("#vault_id").val();
        //         var form = document.getElementById("myForm");
        //   var formData = new FormData(form);


        var formData = $("#myForm").serialize();
        // formData.append('type', "2");
        formData += '&vault_id=' + vault_id;

        if (validateForm('toast')) {

            $.ajax({
                url: 'pages/kasa/main.php',
                type: 'post',
                data: formData,
                dataType: 'json',
                success: function (res) {
                    if (res.status == 200) {
                        vault_actions();
                        toastr.success(
                            'Başarılı',
                            "İşlem Durumu",
                            "success",
                        )
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error: ' + textStatus + ' - ' + errorThrown);
                }

            })

            //$(".modal-backdrop").remove();

        }

    }

</script>
<script>

    $(document).ready(function () {
        const listItems = $(".kasa ul li");

        listItems.on("click", function () {
            listItems.removeClass('clicked');
            // Tıklanan elemana 'clicked' sınıfını ekle
            $(this).addClass('clicked');

        })



    });

    $(".kasa .nav-item ").click(function () {
        var vault_id = $(this).data("params");
        var date = new Date();

        $("#vault_id").val(vault_id)

        $("#vault_date").val(formatDate(date));
        vault_actions(vault_id);
    })


    function vault_actions() {
        var vault_id = $("#vault_id").val();
        $.ajax({
            url: "ajax.php",
            method: "POST",
            data: {
                'vault_id': vault_id,
                'action': 'kasa'
            },
            success: function (data) {


                new DataTable('#example1', {
                    destroy: true,
                    data: data.columns,
                    columns: [
                        { data: 'id' }, // Sütun adı veritabanı kolon adı ile eşleşmelidir
                        { data: 'account_name' },
                        { data: 'amount' },
                        { data: 'company_id' },
                        { data: 'date' },
                        { data: 'description' },
                        { data: 'sub_type' },
                        { data: 'vault_id' },

                    ],
                    responsive: true,
                    autoWidth: true,

                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("Error: " + textStatus + " - " + errorThrown);
            }
        });
    }





</script>