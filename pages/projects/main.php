<?php require_once $_SERVER["DOCUMENT_ROOT"] ."/include/requires.php";


$pageTitle = isset($_SESSION["pageTitle"]) ? $_SESSION["pageTitle"] : "Alınan Projeler"; 
$type = $pageTitle == "Alınan Projeler" ? 1 : 2;

?>

<input type="hidden" id="project-type" readonly disabled name="project_name" value="<?php echo $type; ?>">
<div class="card card-outline card-info">
    <div class="card-header p-2">
        <div class="d-flex justify-content-between">
            <ul class="nav nav-pills">
                    <?php 
                        $list = $pageTitle;
                        $add = "Yeni Proje"
                    ;?>
                <?php if ($pageTitle == "Alınan Projeler"): ?>
                    <?php if (permtrue("alınanProjeler")): ?>
                        <li class="nav-item">
                            <a 
                                class="tabMenu nav-link <?php echo getActiveStatus($list); ?>" 
                                id="liste" href="#list"
                                data-title="<?php echo $list; ?>" 
                                data-toggle="tab">Proje Listesi 
                            </a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (permtrue("verilenProjeler")): ?>
                        <li class="nav-item">
                            <a 
                                class="tabMenu nav-link <?php echo getActiveStatus($list); ?>" 
                                id="liste" href="#list"
                                data-title="<?php echo $list; ?>" 
                                data-toggle="tab">Proje Listesi 
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($pageTitle == "Alınan Projeler"): ?>
                    <?php if (permtrue("alınanProjelerYeni")): ?>
                        <li class="nav-item">
                            <a 
                                class="tabMenu nav-link <?php echo getActiveStatus($add); ?>" 
                                id="yeni" href="#add"
                                data-title="<?php echo $list; ?>" 
                                data-toggle="tab">Yeni Ekle
                            </a>
                        </li>
                       
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (permtrue("verilenProjelerYeni")): ?>
                        <li class="nav-item">
                            <a 
                                class="tabMenu nav-link <?php echo getActiveStatus($add); ?>" 
                                id="yeni" href="#add"
                                data-title="<?php echo $list; ?>" 
                                data-toggle="tab">Yeni Ekle
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <?php
            $params = array("method" => "add", "type" => $type);
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