<?php
require_once "../../plugins/datatables/datatable.php";
require_once "../../include/requires.php";
// echo "acoount_id :" . $account_id ;
if ($_POST && $_POST['method'] == "Delete") {

    $id = $_POST['id'];
    if ($id > 0) {

        $up = $con->prepare("DELETE FROM parameters where id = ? ");
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
            <th>Parametre Adı</th>
            <th>Parametre Türü</th>
            <th>Başlangıç Tarihi</th>
            <th>Bitiş Tarihi</th>
            <th>Değer</th>
            <th>Hesap Türü</th>
            <th>Açıklama</th>
            <th>Aktif mi?</th>
            <th>OLuşturulma Tarihi</th>
            <th>#</th>

        </tr>
    </thead>
    <tbody>


        <?php

        $sql = $con->prepare("Select * from parameters WHERE account_id = ? ORDER BY id desc;");
        $sql->execute(array($account_id));


        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

            ?>
            <tr>
                <td>
                    <?php echo $row["id"]; ?>
                </td>
                <td>
                    <?php echo $row["company_id"]; ?>
                </td>
                <td>
                    <?php echo $row["param_name"]; ?>
                </td>
                <td>
                    <?php echo $row["param_type"]; ?>
                </td>
                <td>
                    <?php echo $row["start_date"]; ?>
                </td>
                <td>
                    <?php echo $row["end_date"]; ?>
                </td>
                <td>
                    <?php echo $row["param_val"]; ?>
                </td>

                <td>
                    <?php echo $row["calc_type"] == 1 ? "Günlük Ücret" : "Saatlik Ücret" ;?>
                </td>
                <td>
                    <?php echo $row["description"]; ?>
                </td>
                <td class="text-center">
                    <?php
                    if ($row["state"] == "on") {
                        echo "<i class='fas fa-check text-green'></i>";
                    }
                    ?>
                </td>
                <td>
                    <?php echo $row["created_at"]; ?>
                </td>
                <td class="">

                    <i class="fa-solid fa-ellipsis-vertical list-button" data-toggle="dropdown"></i>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item edit"><i class="fa-solid fa-edit dropdown-list-icon"></i>
                            <a href="#" onclick="RoutePagewithParams('params/edit','id=<?php echo $row['id'] ?>')"
                                data-title="Parametre Güncelleme">
                                Güncelle
                            </a>
                        </li>

                        <!-- <?php
                        $params = array(
                            "id" => $row["id"],
                            "method" => "Delete"
                        );
                        $params_json = $func->jsonEncode($params);
                        ?> -->

                        <li class="dropdown-item">
                            <i class="fa-solid fa-trash-can dropdown-list-icon"></i>
                            <a href="#" onclick="deleteRecordByAjax('params/main','<?php echo $params_json ?>')">Sil!</a>
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
            <th>Parametre Adı</th>
            <th>Parametre Türü</th>
            <th>Başlangıç Tarihi</th>
            <th>Bitiş Tarihi</th>
            <th>Değer</th>
            <th>Hesap Türü</th>
            <th>Açıklama</th>
            <th>Aktif mi?</th>
            <th>OLuşturulma Tarihi</th>
            <th>#</th>

        </tr>
    </tfoot>
</table>

<!-- /.content -->
<?php
include_once "../../plugins/datatables/datatablescripts.php" ?>