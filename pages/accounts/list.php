<?php

require_once $_SERVER["DOCUMENT_ROOT"] ."/plugins/datatables/datatable.php";
require_once $_SERVER["DOCUMENT_ROOT"] ."/include/requires.php";

// echo "hesap id :" . $account_id;
if ($_POST && $_POST['method'] == "Delete") {
    $id = $_POST['id'];
    if ($id > 0) {

        $up = $con->prepare("DELETE FROM accounts where id = ? ");
        $result = $up->execute(array($id));

    }
}
;

?>

<div class="card card-outline card-info">
    <div class="card-body">

            <table id="example1" class="table table-bordered table-striped table-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">id</th>
                        <th>Adı Soyadı</th>
                        <th>Email Adresi</th>
                        <th>Firma Adı</th>
                        <th>Telefon</th>
                        <th>Durumu</th>
                        <th>Demo Tarihi</th>
                        <th>Oluşturulma Tarihi</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $sql = $con->prepare("Select * from accounts ORDER BY id desc");
                    $sql->execute();
                    $result = $sql->fetchAll();


                    foreach ($result as $key => $value) {

                        ?>
                        <tr>
                            <td class="text-center col-md-1"><?php echo $value["id"] ?></td>
                            <td><?php echo $value["full_name"] ?></td>
                            <td><?php echo $value["email"] ?></td>
                            <td><?php echo $value["company_name"] ?></td>
                            <td><?php echo $value["phone"] ?></td>
                            <td><?php echo $value["state"] == '' ? "Demo" : "" ?></td>
                            <td><?php echo $value["expired_date"] ?></td>
                            <td><?php echo $value["created_at"] ?></td>

                            <td class="text-center text-nowrap col-md-1 pl-3 pr-3">



                                <a class="btn btn-sm bg-gray edit tabMenu" data-tooltip="Düzenle"
                                    href="#edit" data-title="Hesap Güncelle" data-params="<?php echo $value["id"] ?>"><i
                                        class="fa fa-edit"></i></a>


                                <!-- Silme parametrelerini göndermek için oluşturulur -->
                                <?php
                                $params = array(
                                    "id" => $value["id"],
                                    "message" => $value["full_name"] . ' isimli hesabı silmek istiyor musunuz? Devam ettiğiniz takdirde bu hesaba ait tüm firma, personel ve puantaj kayıtları da silinececektir'
                                );
                                $params_json = $func->jsonEncode($params);
                                ?>
                                <button type="button" data-tooltip="Sil" class="btn btn-sm bg-danger"
                                    onclick="deleteRecordByAjax('accounts/main','<?php echo $params_json; ?>')"><i
                                        class="fa fa-trash"></i></button>

                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-center">id</th>
                        <th>Adı Soyadı</th>
                        <th>Email Adresi</th>
                        <th>Firma Adı</th>
                        <th>Telefon</th>
                        <th>Durumu</th>
                        <th>Demo Tarihi</th>
                        <th>Oluşturulma Tarihi</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </tfoot>
            </table>
            <!-- /.content -->

 
    </div>
</div>
<?php
include_once "../../plugins/datatables/datatablescripts.php" ?>

<script>
    $(".edit").click(function () {
        pageTitle("", "Hesap Güncelle");
        var id = $(this).data("params");
        RoutePagewithParams("accounts/edit", "?id=" + id);

    })
</script>