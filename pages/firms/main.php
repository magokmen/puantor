<?php require_once "../../include/requires.php";?>

<div class="card card-outline card-info">
    <div class="card-header p-2">
        <div class="d-flex justify-content-between">

            <ul class="nav nav-pills">
                <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Firma Listesi"
                        data-toggle="tab">Firma Listesi</a>
                </li>
                <li class="nav-item"><a class="tabMenu nav-link" id="yeni" href="#add" data-title="Yeni Firma"
                        data-toggle="tab">Yeni Firma</a></li>

            </ul>
            <?php
            $params = array("method" => "add");
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Yeni Firma"
                onclick="submitFormbyAjax('firms/main','<?php echo $params_json ?>')" class="btn btn-info d-none">
                <i class="fas fa-save mr-2"></i>Kaydet</button>
        </div>
    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="tab-content">

            <!-- /.tab-pane -->
            <div class="tab-pane fade" id="add">

                <?php include "add.php" ?>

            </div>
            <div class="tab-pane fade" id="list">

                <?php include "list.php" ?>
            </div>
        </div>



    </div><!-- /.card-body -->
</div>
<!-- /.card -->

<script>
    $("#liste").on("click", function () {
        $("#save").addClass("d-none");
    })

    $("#yeni").on("click", function () {
        $("#save").removeClass("d-none");
    })
    $(function () {

        var pagetitle = $("#page-title").text();
        if (pagetitle == "Firma Listesi") {
            $("#liste").tab("show");
        }

        if (pagetitle == "Yeni Firma") {
            $("#yeni").tab("show");
        }
    })
    $(function () {
        $(".tabMenu").click(function () {
            var navLinkText = $(this).data("title");
            $("#page-title").text(navLinkText);
            setActiveMenu(this);
        });
    });
</script>