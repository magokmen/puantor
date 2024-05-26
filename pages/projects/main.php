<?php require_once "../../include/requires.php"; ?>
<div class="card card-outline card-info">
    <div class="card-header p-2">
        <div class="d-flex justify-content-between">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Alınan Projeler"
                        data-toggle="tab">Alınan Projeler</a>
                </li>
                <li class="nav-item"><a class="tabMenu nav-link" id="yeni" href="#add" data-title="Yeni Proje"
                        data-toggle="tab">Yeni Proje</a></li>

            </ul>

            <?php
            $params = array("method" => "add");
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Yeni Proje"
                onclick="submitFormbyAjax('projects/main','<?php echo $params_json ?>')"
                class="btn btn-primary d-none">Kaydet</button>
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
    $(function () {

        var pagetitle = $("#page-title").text();
        if (pagetitle == "Alınan Projeler") {
            $("#liste").tab("show");
        }

        if (pagetitle == "Yeni Proje") {
            $("#yeni").tab("show");
        }
    })
    // $("#liste").on("click", function () {
    //     $("#save").addClass("d-none");
    // })

    // $("#yeni").on("click", function () {
    //     $("#save").removeClass("d-none");
    // })


    $(function () {
        $(".tabMenu").click(function () {
            var navLinkText = $(this).data("title");
            $("#page-title").text(navLinkText);
            setActiveMenu(this);
        });
    });
    // $("#edit").click(function() {
    //     var title = $(this).text();
    //     $("#page-title").text(title);
    //     // $("#duzenle").tab("show");
    // })
</script>