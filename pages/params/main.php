<?php
require_once "../../include/requires.php";


if ($_POST && $_POST['method'] == "Delete") {

    $id = $_POST['id'];
    if ($id > 0) {

        $up = $con->prepare("DELETE FROM parameters where id = ? ");
        $result = $up->execute(array($id));
    }
}
?>
<div class="card card-outline card-info">
    <div class="card-header p-2">
        <div class="d-flex justify-content-between">

            <?php if (permtrue("tanımlamalarParametreListesi")): ?>
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Parametre Listesi"
                            data-toggle="tab">Parametre Listesi</a>
                    </li>
                    <?php if (permtrue("tanımlamalarParametreEkle")): ?>
                    <?php endif; ?>
                    <li class="nav-item"><a class="tabMenu nav-link" id="yeni" href="#add" data-title="Parametre Listesi"
                            data-toggle="tab">Yeni Parametre</a>
                    </li>
                <?php endif; ?>
            </ul>
            <?php
            $params = array("method" => "add");
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Yeni Parametre"
                onclick="submitFormReturnJson('params/add','<?php echo $params_json ?>')" class="btn btn-info d-none">
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
<script src="../../src/component.js"></script>