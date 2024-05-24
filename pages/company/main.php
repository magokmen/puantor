<div class="card card-outline card-info">
    <div class="card-header p-2">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Firma Listesi"
                    data-toggle="tab">Firma Listesi</a>
            </li>
            <li class="nav-item"><a class="tabMenu nav-link" id="yeni" href="#add" data-title="Yeni Firma"
                    data-toggle="tab">Yeni Firma</a></li>

            <li class="nav-item" ><a class="tabMenu nav-link " id="guncelle" href="#edit" data-title="Firma Güncelle"
                    data-toggle="tab">Firma Güncelle</a></li>

        </ul>
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

            <div class="tab-pane fade" id="edit">
                <?php include "edit.php" ?>

            </div>
        </div>

    </div><!-- /.card-body -->
</div>
<!-- /.card -->

<script>
    $(function () {

        var pagetitle = $("#page-title").text();
        if (pagetitle == "Firmalarım") {
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