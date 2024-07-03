<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/plugins/datatables/datatable.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/include/requires.php";
$id = $_SESSION['id'];

// Eğer POST metodu ile silme işlemi yapılıyorsa
if ($_POST && $_POST['method'] == "Delete") {
    $id = $_POST['id'];
    if ($id > 0) {
        $up = $con->prepare("DELETE FROM users where id = ?");
        $result = $up->execute(array($id));
    }
}

// Veritabanından kullanıcı verilerini al
$sql1 = $con->prepare("SELECT groups FROM users WHERE id = ?");
$sql1->execute(array($id));
$user = $sql1->fetch();

if ($user && $user['groups'] == 1) {
    $sql = $con->prepare("SELECT * FROM users WHERE account_id = ? ORDER BY id DESC");
    $sql->execute(array($account_id));
} else {
    $sql = $con->prepare("SELECT * FROM users WHERE account_id = ? AND groups != 1 ORDER BY id DESC");
    $sql->execute(array($account_id));
}

$result = $sql->fetchAll();
?>

<table id="example1" class="table table-bordered table-striped table-sm table-hover">
    <thead>
        <tr>
            <th class="text-center">id</th>
            <th>Adı Soyadı</th>
            <th>Telefon</th>
            <th>Email Adresi</th>
            <th>Pozisyon</th>
            <th>Durumu</th>
            <th class="text-center">İşlemler</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $key => $value): ?>
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
                <td>
                    <?php
                    $ids = $value["groups"];
                    $sql = "SELECT roleName FROM userroles WHERE id = ?";
                    $stmt = $con->prepare($sql);
                    $stmt->execute([$ids]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo $result ? $result["roleName"] : "Hiçbir sonuç bulunamadı.";
                    ?>
                </td>
                <td class="text-center">
                    <?php $checked = $value["isActive"] == 1 ? "checked" : ""; ?>
                    <input type="checkbox" class="check" <?php echo $checked; ?> data-on="Aktif" data-off="Pasif"
                        data-offstyle="danger" data-user-id="<?php echo $value['id']; ?>">

                </td>
                <td class="text-center text-nowrap col-md-1 pl-3 pr-3">
                    <?php if (permtrue("kullanıcıDüzenle")): ?>
                        <button class="btn btn-sm bg-gray" data-tooltip="Düzenle" onclick="RoutePage('users/edit', this)"
                            data-title="Kullanıcı Düzenle" data-params="id=<?php echo $value["id"] ?>"><i
                                class="fa fa-edit"></i></button>
                    <?php endif; ?>

                    <?php
                    $params = array(
                        "id" => $value["id"],
                        "message" => $value["full_name"] . ' isimli kullanıcı silinecektir. Devam etmek istiyor musunuz?'
                    );
                    $params_json = $func->jsonEncode($params);
                    ?>
                    <?php if (permtrue("kullanıcıDüzenSil")): ?>
                        <button type="button" data-tooltip="Sil" class="btn btn-sm bg-danger"
                            onclick="deleteRecordByAjax('users/main','<?php echo $params_json; ?>')"><i
                                class="fa fa-trash"></i></button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th class="text-center">id</th>
            <th>Adı Soyadı</th>
            <th>Telefon</th>
            <th>Email Adresi</th>
            <th>Pozisyon</th>
            <th>Durumu</th>
            <th class="text-center">İşlemler</th>
        </tr>
    </tfoot>
</table>

<?php include_once $_SERVER["DOCUMENT_ROOT"] . "/plugins/datatables/datatablescripts.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.check').change(function () {
            var isActive = $(this).is(':checked') ? 1 : 0;
            var userId = $(this).data('user-id');
            var action = 'update_status';

            $.ajax({
                type: "POST",
                url: "ajax.php",
                data: { action: action, isActive: isActive, userId: userId },
                success: function (response) {
                    console.log(response);
                }
            });
        });
    });
</script>