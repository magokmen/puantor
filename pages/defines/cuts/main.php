<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/requires.php";
if ($_POST && $_POST['method'] == "Delete") {

    $id = $_POST['id'];
    deleteRecordbyPhp($id, "cut_types", "defines/cuts/main", "Kesinti Türü Listesi");
    return;
};
?>

<div class="card card-outline card-info">
    <div class="card-header p-2">
        <div class="d-flex justify-content-between">
                    <?php 
                        $link="Kesinti Türü Tanımlama";
                        $list = "Kesinti Türü Listesi";
                        $add = "Yeni Kesinti Türü"
                    ;?>
            <ul class="nav nav-pills">
                <?php if (permtrue("tanımlamalarKesintiListesi")) : ?>
                    <a  class="tabMenu nav-link <?php echo getActiveStatus($list); ?>" 
                            id="liste" href="#list" 
                            data-title="<?php echo $list; ?>" 
                            data-link="<?php echo $list; ?>" 
                            data-toggle="tab">Liste
                        </a>
                <?php endif; ?>
                <?php if (permtrue("tanımlamalarKesintiTuruEkle")) : ?>
                    <a  class="tabMenu nav-link <?php echo getActiveStatus($add); ?>" 
                            id="yeni" href="#add" 
                            data-title="<?php echo $add; ?>" 
                            data-link="<?php echo $list; ?>" 
                            data-toggle="tab">Yeni Ekle
                        </a>
                <?php endif; ?>
            </ul>
            <?php
            $params = array("method" => "add");
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Yeni Kesinti" onclick="submitFormReturnJson('defines/cuts/add','<?php echo $params_json ?>')" class="btn btn-info d-none">
                <i class="fas fa-save mr-2"></i>Kaydet</button>
        </div>


    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="tab-content">

            <!-- /.tab-pane -->
            <div class="tab-pane fade <?php echo getActiveStatus($add) . ' show' ;?> " id="add">

                <?php include "add.php" ?>

            </div>
            <div class="tab-pane fade <?php echo getActiveStatus($list) . ' show' ;?> " id="list">

                <?php include "list.php" ?>
            </div>
        </div>

    </div><!-- /.card-body -->
</div>
<!-- /.card -->
<script src="../../src/component.js"></script>