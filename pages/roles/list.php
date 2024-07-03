<?php
// require_once ROOT_PATH . "/plugins/datatables/datatable.php";

$id = $_SESSION['id'];

if ($_POST && isset($_POST["action"]) == "delete-roles") {
    $id = $_POST["id"];

    $sql = $con->prepare("DELETE from userroles  WHERE id = ?");
    $sql->execute(array($id));
}

?>
<style>
    .card {
        word-wrap: normal;

    }
</style>
<div style="margin-bottom:8px" class="row">

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
            <th>Yetki Adı</th>
            <th>Açıklama</th>

            <th class="text-center" style="max-width:10px">İşlemler</th>
        </tr>
    </thead>
    <?php
    // users tablosundaki id alanını kontrol et
    $sql = $con->prepare("SELECT groups FROM users WHERE id = ?");
    $sql->execute(array($id));
    $user = $sql->fetch();

    if ($user && $user['groups'] == 1) {
        // groups alanındaki değer 1 ise normal işlemi yap
        $sql = $con->prepare("SELECT * FROM userroles WHERE account_id = ? ORDER BY id DESC");
        $sql->execute(array($account_id));
    } else {
        // groups alanındaki değer 1 değilse, id alanı 1 dışında olanları göster
        $sql = $con->prepare("SELECT * FROM userroles WHERE account_id = ? AND id != 1 ORDER BY id DESC");
        $sql->execute(array($account_id));
    }

    $result = $sql->fetchAll();
    ?>

    <tbody>
        <?php foreach ($result as $key => $value): ?>
            <tr>
                <td>
                    <?php echo $value["id"] ?>
                </td>
                <td>
                    <?php echo $value["roleName"] ?>
                </td>
                <td>
                    <?php echo $value["roleDescription"] ?>
                </td>

                <td class="text-center">
                    <i class="fa-solid fa-ellipsis list-button" data-toggle="dropdown"></i>
                    <ul class="dropdown-menu">
                        <?php if (permtrue("yetkilerGüncelle")): ?>
                            <li class="dropdown-item"><i class="fa-solid fa-edit dropdown-list-icon"></i><a href="#"
                                    onclick="RoutePagewithParams('roles/edit','id=<?php echo $value['id'] ?>&type=<?php echo $type; ?>')"
                                    data-title="Yetki Grubu Düzenle">Güncelle</a></li>
                        <?php endif; ?>
                        <?php if (permtrue("yetkilerYetkiDüzenle")): ?>
                            <li class="dropdown-item"><i class="fa-solid fa-edit dropdown-list-icon"></i><a href="#"
                                    onclick="RoutePage('roles/auths/add',this)" data-params="id=<?php echo $value['id'] ?>"
                                    data-title="Yetki Grubu Düzenle">Yetkileri Düzenle</a></li>
                        <?php endif; ?>
                        <?php
                        $params = array(
                            "id" => $value["id"],
                            "action" => "delete-project",
                            "message" => $value["roleName"] . ' isimli yetkiyi silmek istiyor musunuz? Devam ettiğiniz takdirde yetkiye ait ödemeler ve personeller silinecektir.'
                        );
                        $params_json = json_encode($params);
                        ?>
                        <?php if (permtrue("yetkilerSil")): ?>
                            <li class="dropdown-item"><i class="fa-solid fa-trash-can dropdown-list-icon"></i><a href="#"
                                    onclick="deleteRecordByAjax('roles/main','<?php echo $params_json ?>')">Sil!</a></li>
                        <?php endif; ?>
                    </ul>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>

        <tr>
            <th>id</th>
            <th>Yetki Adı</th>
            <th>Açıklama</th>
            <th class="text-center" style="max-width:8px">İşlemler</th>
        </tr>
    </tfoot>
</table>

<!-- /.content -->
<?php
// include_once "../../plugins/datatables/datatablescripts.php" ?>