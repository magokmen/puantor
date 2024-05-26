<?php 
require_once "../../plugins/datatables/datatable.php";
require_once "../../include/requires.php";

// echo "hesap id :" . $account_id;
if ($_POST && $_POST['method'] == "Delete") {
    $id = $_POST['id'];
    if ($id > 0) {

        $up = $con->prepare("DELETE FROM users where id = ? ");
        $result = $up->execute(array($id));

    }
}
;

?>

<table id="example1" class="table table-bordered table-striped table-sm table-hover ">
    <thead>
        <tr>
            <th class="text-center">id</th>
            <th>Adı Soyadı Adı</th>
            <th>Telefon</th>
            <th>Email Adresi</th>
            <th class="text-center">İşlemler</th>
        </tr>
    </thead>
    <tbody>

        <?php
        $sql =  $con->prepare("Select * from users where account_id = ? ORDER BY id desc");
        $sql->execute(array($account_id));
        $result = $sql->fetchAll();


        foreach ($result as $key => $value) {

            ?>
            <tr>
                <td class="text-center col-md-1">
                    <?php echo $value["id"] ?>
                </td>
                <td>
                    <?php echo $value["full_name"] ?>
                </td>
                <td>
                    <?php echo $value["phone"] ?>
                </td>
                <td>
                    <?php echo $value["email"] ?>
                </td>

                <td class="text-center text-nowrap col-md-1 pl-3 pr-3">

                    
                    <button class="btn btn-sm bg-gray" data-tooltip="Düzenle" onclick="RoutePage('users/edit', this)"
                        data-title="Kullanıcı Düzenle" data-params="id=<?php echo $value["id"] ?>"><i
                            class="fa fa-edit"></i></button>

                    <!-- Silme parametrelerini göndermek için oluşturulur -->
                    <?php
                    $params = array(
                        "id" => $value["id"],
                        "message" => $value["full_name"] .' isimli kullanıcı silinecektir. Devam etmek istiyor musunuz?'
                    );
                    $params_json = $func->jsonEncode($params);
                    ?>
                    <button type="button" data-tooltip="Sil" class="btn btn-sm bg-danger"
                        onclick="deleteRecordByAjax('users/main','<?php echo $params_json; ?>')"><i
                            class="fa fa-trash"></i></button>
                </td>
            </tr>

        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
        <th class="text-center">id</th>
            <th>Adı Soyadı Adı</th>
            <th>Telefon</th>
            <th>Email Adresi</th>
            <th class="text-center">İşlemler</th>
        </tr>
    </tfoot>
</table>
<!-- /.content -->

<?php
include_once "../../plugins/datatables/datatablescripts.php" ?>
