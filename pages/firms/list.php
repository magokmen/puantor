<?php
require_once $_SERVER["DOCUMENT_ROOT"] ."/plugins/datatables/datatable.php";
require_once $_SERVER["DOCUMENT_ROOT"] ."/include/requires.php";



if ($_POST && isset($_POST["action"]) == "delete-project") {
    $id = $_POST["id"];
    $sql = $con->prepare("SELECT * FROM firms where id = ?");
    $sql->execute(array($id));
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    $file_path = "../../files/" . $result["file_name"];

    if ($result["file_name"]) {

        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    $sql = $con->prepare("DELETE from projects  WHERE id = ?");
    $sql->execute(array($id));


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
            <th class="text-center">id</th>
            <th>Firma Adı</th>
            <th>Firma Yetkilisi</th>
            <th>Şehir</th>
            <th>İlçe</th>
            <th>Telefon</th>
            <th>Email</th>
            <th>Adres</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>


        <?php
        $sql = $con->prepare("Select * from firms WHERE account_id = ? ORDER BY id desc");
        $sql->execute(array($account_id));
        $result = $sql->fetchAll();


        foreach ($result as $key => $value) {

            ?>
            <tr>
                <td class="text-center">
                    <?php echo $value["id"] ?>
                </td>
                <td>
                    <?php echo $value["firm_name"] ?>
                </td>
                <td>
                    <?php echo $value["firm_official"] ?>
                </td>
                <td><?php echo $func->getCityName($value["city"]) ?></td>
                <td><?php echo $func->getTownName($value["town"]) ?></td>
                <td><?php echo $value["phone"] ?></td>
                <td><?php echo $value["email"] ?></td>
                <td><?php echo $value["address"] ?></td>

                <td class="">

                <i class="fa-solid fa-ellipsis list-button" data-toggle="dropdown"></i>
                    <ul class="dropdown-menu">
                        <?php if (permtrue("firmaDüzenle")): ?>
                            <li class="dropdown-item"><i class="fa-solid fa-edit dropdown-list-icon"></i><a href="#"
                                    onclick="RoutePagewithParams('firms/edit', 'id=<?php echo $value['id'] ?>')"
                                    data-title="Firma Düzenle">
                                    Güncelle
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (permtrue("firmaHesap")): ?>
                            <li class="dropdown-item"><i class="fa-solid fa-file-pen dropdown-list-icon"></i><a href="#">Hesap
                                    Hareketleri
                                </a></li>
                            <li class="dropdown-divider"></li>
                        <?php endif; ?>
                        <?php
                        $params = array(
                            "id" => $value["id"],
                            "action" => "delete-project",
                            "message" => $value["firm_name"] . ' isimli firmayı silmek istiyor musunuz?Devam ettiğiniz takdirde firmaya ait ödemeler ve personeller silinecektir.'
                        );
                        $params_json = $func->jsonEncode($params);
                        ?>
                        <?php if (permtrue("firmaSil")): ?>
                            <li class="dropdown-item"><i class="fa-solid fa-trash-can dropdown-list-icon"></i><a href="#"
                                    onclick="deleteRecordByAjax('firms/main','<?php echo $params_json ?>')">Sil!</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </td>
            </tr>

        <?php } ?>
    </tbody>
    <tfoot>

        <tr>
            <th class="text-center">id</th>
            <th>Firma Adı</th>
            <th>Firma Yetkilisi</th>
            <th>Şehir</th>
            <th>İlçe</th>
            <th>Telefon</th>
            <th>Email</th>
            <th>Adres</th>
            <th>#</th>
        </tr>
    </tfoot>
</table>

<!-- /.content -->
<?php
include_once $_SERVER["DOCUMENT_ROOT"] ."/plugins/datatables/datatablescripts.php" ?>