<?php
require_once "../../plugins/datatables/datatable.php";
include_once "../../config/connect.php";
include_once "../../config/functions.php";



if ($_POST && $_POST['method'] == "Delete") {

    $id = $_POST['id'];
    if ($id > 0) {

        $up = $con->prepare("DELETE FROM projects where id = ? ");
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
            <th>Proje Adı</th>
            <th>Bütçe</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>


        <?php
        $sql = "Select * from projects ORDER BY id desc;";
        $sql = $con->prepare($sql);
        $sql->execute();
        $result = $sql->fetchAll();


        foreach ($result as $key => $value) {

            ?>
            <tr>
                <td>
                    <?php echo $value["id"] ?>
                </td>
                <td>
                    <?php echo $value["company_id"] ?>
                </td>
                <td>
                    <?php echo $value["project_name"] ?>
                </td>
                <td>
                    <?php echo $value["budget"]?>
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
                        $params = array("id" => $value["id"], "delValue" => $value["full_name"]);
                        $params_json = $func->jsonEncode($params);
                        ?>

                        <li class="dropdown-item"><i class="fa-solid fa-trash-can dropdown-list-icon"></i><a href="#"
                                onclick="deleteRecordByAjax('person/main','<?php echo $params_json ?>')">Sil!</a></li>
                    </ul>

                    <!-- <button class="btn btn-sm bg-gray" onclick="RoutePage('/offers/edit', this)" data-title="Teklif Düzenle"
                        data-params="id=<?php echo $value["id"] ?>"><i class="fas fa-edit"></i></button> -->
                    <!-- 
                    <button type="button" class="btn btn-sm bg-danger"
                        onclick="deleteRecordByAjax('offers/main','<?php echo $value['id'] ?>')"><i
                            class="fa fa-trash"></i></button> -->
                </td>
            </tr>

        <?php } ?>
    </tbody>
    <tfoot>

        <tr>
            <th>id</th>
            <th>Firma Adı</th>
            <th>Proje Adı</th>
            <th>Bütçe</th>
            <th>#</th>
        </tr>
    </tfoot>
</table>

<!-- /.content -->
<?php
include_once "../../plugins/datatables/datatablescripts.php" ?>