<?php
require_once $_SERVER["DOCUMENT_ROOT"] ."/include/requires.php";
?>
<div class="card card-outline card-info">
    <div class="card-header p-2">
        <ul class="nav nav-pills">          
                    <?php 
                        $list = "Kullanıcı Listesi";
                        $add = "Yeni Kullanıcı";
                    ?>
            <?php if (permtrue("kullanıcıListele")): ?>
                <li class="nav-item">
                        <a  class="tabMenu nav-link <?php echo getActiveStatus($list); ?>" 
                            id="liste" href="#list" 
                            data-title="<?php echo $list; ?>" 
                            data-link="<?php echo $list; ?>" 
                            data-toggle="tab">Liste
                        </a>
                    </li>
            <?php endif; ?>
            <?php if (permtrue("kullanıcılarYeni")): ?>

                <li class="nav-item">
                        <a 
                            class="tabMenu nav-link <?php echo getActiveStatus($add); ?>" 
                            id="yeni" href="#add" 
                            data-title="<?php echo $add; ?>" 
                            data-link="<?php echo $add; ?>" 
                            data-toggle="tab">Yeni Ekle
                        </a>
                    </li>
            <?php endif; ?>


        </ul>
    </div><!-- /.card-header -->
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane <?php echo getActiveStatus($add) . ' show' ;?> " id="add">
                <?php include "add.php" ?>
            </div>
            <div class="tab-pane <?php echo getActiveStatus($list) . ' show' ;?> " id="list">

                <?php include "list.php" ?>
            </div>
            <!-- /.tab-pane -->

        </div>
        <!-- /.tab-content -->
    </div><!-- /.card-body -->
</div>
<!-- /.card -->
<script src="../../src/component.js"></script>