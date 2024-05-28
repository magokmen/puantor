<?php
require_once "../../plugins/datatables/datatable.php";




if ($_POST && isset($_POST["action"]) == "delete-project") {
    $id = $_POST["id"];
    $sql = $con->prepare("SELECT * FROM projects where id = ?");
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
        <button type="button" class="btn btn-default">Hakediş Listesi</button>
    </div>

</div>
<!-- Main content -->

<table id="example1" class="table table-bordered table-striped table-sm table-hover">
    <thead>

        <tr>
            <th>id</th>
            <th>Şirket Adı</th>
            <th>Firma Adı</th>
            <th>Proje Adı</th>
            <th>Başl.Bütçe</th>
            <th>Şehir</th>
            <th>İlçe</th>
            <th>Başlama Tarihi</th>
            <th>Adres</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>


        <?php
        $sql = $con->prepare("Select * from projects WHERE type = ? and account_id = ? ORDER BY id desc");
        $sql->execute(array($type,$account_id));
        $result = $sql->fetchAll();


        foreach ($result as $key => $value) {

            ?>
            <tr>
                <td>
                    <?php echo $value["id"] ?>
                </td>
                <td>
                    <?php echo $func->getCompanyName($value["company_id"]) ?>
                </td>
                <td>
                    <?php echo $func->getFirmName($value["firm_id"]) ?>
                </td>
                <td>
                    <?php echo $value["project_name"] ?>
                </td>
                <td><?php echo $value["budget"] ?></td>
                <td><?php echo $func->getCityName($value["city"]) ?></td>
                <td><?php echo $func->getTownName($value["town"]) ?></td>
                <td><?php echo $value["start_date"] ?></td>
                <td><?php echo $value["address"] ?></td>

                <td class="">

                    <i class="fa-solid fa-ellipsis-vertical list-button" data-toggle="dropdown"></i>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"><i class="fa-solid fa-edit dropdown-list-icon"></i><a href="#"
                                onclick="RoutePagewithParams('projects/edit','id=<?php echo $value['id'] ?>&type=<?php echo $type ;?>')" 
                                data-title="Proje Düzenle">
                                Düzenle
                            </a>
                        </li>



                        <li class="dropdown-item"><i class="fa-solid fa-print dropdown-list-icon"></i>
                            <a href="#" onclick="RoutePage('offers/edit', this)" data-params="id=<?php echo $value["id"] ?>"
                                data-title="Teklif Düzenle">
                                Puantaj Listesi
                            </a>
                        </li>
                        <li class="dropdown-item"><i class="fa-solid fa-file-pen dropdown-list-icon"></i><a href="#">Hesap
                                Hareketleri
                            </a></li>
                        <li class="dropdown-divider"></li>

                        <?php
                        $params = array(
                            "id" => $value["id"],
                            "action" => "delete-project",
                            "type" => $type,
                            "message" => $value["project_name"] . ' isimli projeyi silmek istiyor musunuz?Devam ettiğiniz takdirde projeye ait ödemeler ve personeller silinecektir.'
                        );
                        $params_json = $func->jsonEncode($params);
                        ?>

                        <li class="dropdown-item"><i class="fa-solid fa-trash-can dropdown-list-icon"></i><a href="#"
                                onclick="deleteRecordByAjax('projects/main','<?php echo $params_json ?>')">Sil!</a></li>
                    </ul>
                </td>
            </tr>

        <?php } ?>
    </tbody>
    <tfoot>

        <tr>
        <th>id</th>
            <th>Şirket Adı</th>
            <th>Firma Adı</th>
            <th>Proje Adı</th>
            <th>Başl.Bütçe</th>
            <th>Şehir</th>
            <th>İlçe</th>
            <th>Başlama Tarihi</th>
            <th>Adres</th>
            <th>#</th>
        </tr>
    </tfoot>
</table>

<!-- /.content -->
<?php
include_once "../../plugins/datatables/datatablescripts.php" ?>