<?php require_once "../../include/requires.php";

$type = isset($_GET["type"]) ? $_GET["type"] : @$_POST["type"] ;
echo "type : " . $type ;
$pageTitle = ($type == 1) ? "Alınan Projeler" : "Verilen Projeler";
?>

<input type="hidden" id="project-type" readonly disabled name="project_name" value="<?php echo $type; ?>">
<div class="card card-outline card-info">
    <div class="card-header p-2">
        <div class="d-flex justify-content-between">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="<?php echo $pageTitle ;?>"
                        data-toggle="tab"><?php echo $pageTitle ;?></a>
                </li>
                <li class="nav-item"><a class="tabMenu nav-link" id="yeni" href="#add" data-title="<?php echo $pageTitle ;?>"
                        data-toggle="tab">Yeni Proje</a></li>

            </ul>

            <?php
            $params = array("method" => "add","type" => $type);
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Yeni Proje"
                onclick="submitFormbyAjax('projects/main','<?php echo $params_json ?>')" class="btn btn-info d-none">
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
