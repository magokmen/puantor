<?php
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
        <button type="button" class="btn btn-default">Hakediş Listesi</button>
    </div>

</div>
<!-- Main content -->

<table id="example1" class="table table-bordered table-striped table-sm table-hover">
    <thead>

        <tr>
            <th>id</th>
            <th>Firma Adı</th>
            <th>Yetkili</th>
            <th>Email</th>
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
                    <?php echo $row["full_name"]; ?>
                </td>
                <td>
                    <?php echo $row["email"]; ?>
                </td>
                <td>
                    Aktif
                </td>
                <td class="">

                    <i class="fa-solid fa-ellipsis-vertical list-button" data-toggle="dropdown"></i>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"><i class="fa-solid fa-edit dropdown-list-icon"></i><a href="#"
                                onclick="RoutePage('offers/edit', this)" data-params="id=<?php echo $value["id"] ?>"
                                data-title="Teklif Düzenle">
                                Düzenle
                            </a>
                        </li>

                        <!-- <?php
                        $params = array("id" => $row["id"], "delValue" => $row["full_name"]);
                        $params_json = $func->jsonEncode($params);
                        ?> -->

                        <li class="dropdown-item">
                            <i class="fa-solid fa-trash-can dropdown-list-icon"></i>
                            <a href="#" onclick="deleteRecordByAjax('company/main','<?php echo $params_json ?>')">Sil!</a>
                        </li>
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
            <th>Email</th>
            <th>Durum</th>
            <th>#</th>


        </tr>
    </tfoot>
</table>

<!-- /.content -->
<?php
include_once "../../plugins/datatables/datatablescripts.php" ?>