<div class="card card-outline card-info">
    <div class="card-header p-2">
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Kullanıcı Listesi"
                    data-toggle="tab">Tüm Kullanıcılar</a>
            </li>
            <li class="nav-item"><a class="tabMenu nav-link" id="yeni" href="#add" data-title="Yeni Kullanıcı"
                    data-toggle="tab">Yeni Kullanıcı</a></li>

        </ul>
    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane" id="add">
                <?php include "add.php" ?>
            </div>
            <div class="tab-pane" id="list">

                <?php include "list.php" ?>
            </div>
            <!-- /.tab-pane -->

        </div>
        <!-- /.tab-content -->
    </div><!-- /.card-body -->
</div>
<!-- /.card -->
<script src="../../src/component.js"></script>