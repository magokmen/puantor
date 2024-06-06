<?php
require_once "../../plugins/datatables/datatable.php";
require_once "../../include/requires.php";
//echo "acoount_id :" . $account_id ;
if ($_POST && $_POST['method'] == "Delete") {

    $id = $_POST['id'];
    if ($id > 0) {

        $up = $con->prepare("DELETE FROM companies where id = ? ");
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
            <th>Yetkili</th>
            <th>Telefon</th>
            <th>Email</th>
            <th>Açılış Tarihi</th>
            <th>Kapanış Tarihi</th>
            <th>Durum</th>
            <th>#</th>

        </tr>
    </thead>
    <tbody>


        <?php

        $sql = $con->prepare("Select * from companies WHERE account_id = ? ORDER BY id desc;");
        $sql->execute(array($account_id));


        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

            ?>
            <tr>
                <td>
                    <?php echo $row["id"]; ?>
                </td>
                <td>
                    <?php echo $row["company_name"]; ?>
                </td>
                <td>
                    <?php echo $row["company_official"]; ?>
                </td>
                <td>
                    <?php echo $row["phone"]; ?>
                </td>
                <td>
                    <?php echo $row["email"]; ?>
                </td>
                <td>
                    <?php echo $row["open_date"]; ?>
                </td>
                <td>
                    <?php echo $row["close_date"]; ?>
                </td>
                <td>
                    <?php echo ($row["close_date"] == '') ? "Aktif" : "Pasif"; ?>
                </td>
                <td class="">

                <i class="fa-solid fa-ellipsis list-button" data-toggle="dropdown"></i>
                    <ul class="dropdown-menu">
                        <?php if (permtrue("şirketlerimGüncelle")): ?>
                            <li class="dropdown-item edit"><i class="fa-solid fa-edit dropdown-list-icon"></i>
                                <a href="#" onclick="RoutePagewithParams('company/edit','id=<?php echo $row['id'] ?>')"
                                    data-title="Şirket Güncelleme">
                                    Güncelle
                                </a>
                            </li>
                        <?php endif; ?>

                        <!-- <?php
                        $params = array("id" => $row["id"], "message" => $row["company_name"]);
                        $params_json = $func->jsonEncode($params);
                        ?> -->
                        <?php if (permtrue("şirketlerimSil")): ?>
                            <li class="dropdown-item">
                                <i class="fa-solid fa-trash-can dropdown-list-icon"></i>
                                <a href="#" onclick="deleteRecordByAjax('company/main','<?php echo $params_json ?>')">Sil!</a>
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
            <th>Yetkili</th>
            <th>Telefon</th>
            <th>Email</th>
            <th>Açılış Tarihi</th>
            <th>Kapanış Tarihi</th>
            <th>Durum</th>
            <th>#</th>

        </tr>
    </tfoot>
</table>

<!-- /.content -->
<?php
include_once "../../plugins/datatables/datatablescripts.php" ?>