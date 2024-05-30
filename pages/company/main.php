<?php require_once "../../include/requires.php"; ?>

<div class="card card-outline card-info">
    <div class="card-header p-2">

        <div class="d-flex justify-content-between">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Şirket Listesi"
                        data-toggle="tab">Şirket Listesi</a>
                </li>
                <li class="nav-item"><a class="tabMenu nav-link" id="yeni" href="#add" data-title="Şirket Listesi"
                        data-toggle="tab">Yeni Şirket</a></li>

            </ul>


            <?php
            $params = array("method" => "add");
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" data-title="Yeni Firma" id="save"
                onclick="submitFormbyAjax('company/main','<?php echo $params_json ?>')"
                class="btn btn-info d-none"><i class="fas fa-save mr-2"></i> Kaydet</button>

                
        </div><!-- /.card-header -->
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
<script src="../../src/component.js"></script>
