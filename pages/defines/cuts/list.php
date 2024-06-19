

<?php
require_once $_SERVER["DOCUMENT_ROOT"] ."/plugins/datatables/datatable.php";
require_once $_SERVER["DOCUMENT_ROOT"] ."/include/requires.php";
// echo "acoount_id :" . $account_id ;
if ($_POST && $_POST['method'] == "Delete") {

    $id = $_POST['id'];
    if ($id > 0) {

        $up = $con->prepare("DELETE FROM cut_types where id = ? ");
        $result = $up->execute(array($id));
    }
}
;


?>

<div style="margin-bottom:10px" class="row">

    <div class="btn-group">
        <button type="button" class="btn btn-default">Pdf</button>
        <button type="button" class="btn btn-default">XLS</button>
    </div>

</div>
<!-- Main content -->

<table id="example1" class="table table-bordered table-striped table-sm table-hover">
    <thead>

        <tr>
            <th>id</th>
            <th>Firma Adı</th>
            <th>Kesinti Adı</th>
             <th>Oluşturulma Tarihi</th>
            <th>#</th>

        </tr>
    </thead>
    <tbody>


        <?php

        $sql = $con->prepare("Select * from cut_types WHERE account_id = ? ORDER BY id desc;");
        $sql->execute(array($account_id));


        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

            ?>
            <tr>
                <td>
                    <?php echo $row["id"]; ?>
                </td>
                <td>
                    <?php echo $func->getCompanyName($row["company_id"]); ?>
                </td>
                <td>
                    <?php echo $row["type_name"]; ?>
                </td>
                
                <td>
                    <?php echo $row["created_at"]; ?>
                </td>
                <td class="text-nowrap">

                <i class="fa-solid fa-ellipsis list-button" data-toggle="dropdown"></i>
                    <ul class="dropdown-menu">
                        <!-- <?php if (permtrue("tanımlamalarKesintiGuncelle")): ?> -->

                            <li class="dropdown-item edit"><i class="fa-solid fa-edit dropdown-list-icon"></i>
                                <a href="#" onclick="RoutePagewithParams('defines/cuts/edit','id=<?php echo $row['id'] ?>')"
                                    data-title="Kesinti Türü Güncelleme">
                                    Güncelle
                                </a>
                            </li>
                        <!-- <?php endif; ?> -->
                        <!-- <?php
                        $params = array(
                            "id" => $row["id"],
                            "method" => "Delete"
                        );
                        $params_json = $func->jsonEncode($params);
                        ?> -->
                        <?php if (permtrue("tanımlamalarKesintiTuruSil")): ?>
                            <li class="dropdown-item">
                                <i class="fa-solid fa-trash-can dropdown-list-icon"></i>
                                <a href="#" onclick="deleteRecordByAjax('defines/cuts/main','<?php echo $params_json ?>')">Sil!</a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </td>
            </tr>

        <?php } ?>
    </tbody>
    <tfoot>

        <tr>
        <th>id</th>
            <th>Firma Adı</th>
            <th>Kesinti Adı</th>
             <th>Oluşturulma Tarihi</th>
            <th>#</th>

        </tr>
    </tfoot>
</table>

<!-- /.content -->
<?php
include_once $_SERVER["DOCUMENT_ROOT"] ."/plugins/datatables/datatablescripts.php" ?>