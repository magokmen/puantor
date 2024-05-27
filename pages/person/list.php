


<?php
require_once "../../plugins/datatables/datatable.php";


if ($_POST && $_POST['method'] == "Delete") {

    $id = $_POST['id'];
    if ($id > 0) {

        $up = $con->prepare("DELETE FROM person where id = ? ");
        $result = $up->execute(array($id));
    }
}
;

?>

<div style="margin-bottom:10px" class="row">

    <div class="btn-group">
        <a type="button" href="pages/person/toxls.php" class="btn btn-default">XLS</a>
        <a type="button" href="#" class="btn btn-default">PDF</a>
        <button type="button" class="btn btn-default">Hakediş Listesi</button>
    </div>

</div>
<!-- Main content -->

<table id="example1" class="table table-bordered table-striped table-sm table-hover">
    <thead>

        <tr>
            <th>id</th>
            <th>Adı Soyadı</th>
            <th>Firma Adı</th>
            <th>Proje Adı</th>
            <th>Sigorta No</th>
            <th>Telefon</th>
            <th>Adres</th>
            <th>Günlük Ücreti</th>
            <th>Durumu</th>
            <th>Email</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>


        <?php
        $sql = $con->prepare("SELECT p.*,c.account_id,c.company_name FROM `person` p
                                LEFT JOIN companies c on c.id = p.company_id
                                WHERE c.account_id = ?
                                ORDER BY p.id desc");
        $sql->execute(array($account_id));
        $result = $sql->fetchAll();


        foreach ($result as $key => $value) {

            ?>
            <tr>
                <td>
                    <?php echo $value["id"] ?>
                </td>

                <td>
                    <?php echo $value["full_name"] ?>
                </td>

                <td>
                    <?php echo $func->getCompanyName($value["company_id"]); ?>
                </td>
                <?php $projectNames = $func->getProjectNames($value["project_id"]); ?>
                <td data-tooltip="<?php echo $projectNames; ?>">
                    <?php
                    //Personelin birden fazla projede çalışması durumunda veritabanındaki 
                    //değer parçalanarak proje isimlerine çevrilir
                    echo $func->shortProjectsName($projectNames) ?>
                </td>

                <td>
                    <?php $id = $value["sigorta_no"]; ?>
                </td>

                <td>
                    <?php echo $value["phone"] ?>
                </td>

                <td>
                    <?php echo $value["address"] ?>
                </td>

                <td>
                    <?php echo $value["daily_wages"] ?>
                </td>

                <td>
                    <?php echo $value["state"] ?>
                </td>

                <td>
                    <?php echo $value["email"] ?>
                </td>

                <td class="">

                    <i class="fa-solid fa-ellipsis-vertical list-button" data-toggle="dropdown"></i>
                    <ul class="dropdown-menu">
                        <li class="dropdown-item"><i class="fa-solid fa-edit dropdown-list-icon"></i>
                            <a href="#" onclick="RoutePagewithParams('person/edit','id=<?php echo $value['id'] ?>')"
                                data-title="Personel Güncelleme">
                                Güncelle
                            </a>
                        </li>

                        <li class="dropdown-item"><i class="fa-solid fa-calendar-days dropdown-list-icon"></i><a href="#"
                                onclick="RoutePage('offers/edit', this)" data-params="id=<?php echo $value["id"] ?>"
                                data-title="Puantaj Listesi">
                                Puantaj listesi
                            </a>
                        </li>
                        <li class="dropdown-item"><i class="fa-regular fa-thumbs-down dropdown-list-icon"></i></i><a href="#"
                                onclick="RoutePage('persone/deduction', this)" data-params="id=<?php echo $value["id"] ?>"
                                data-title="Kesinti Ekle">
                                Kesinti Ekle
                            </a>
                        </li>



                        <li class="dropdown-item"><i class="fa-solid fa-file-pen dropdown-list-icon"></i><a href="#">Hesap
                                Hareketleri
                            </a></li>
                        <li class="dropdown-divider"></li>

                        <?php
                        $params = array(
                            "id" => $value["id"],
                            "message" => $value["full_name"] . ' isimli personel silinecektir.Devam etmek istiyor musunuz?'
                        );
                        $params_json = $func->jsonEncode($params);
                        ?>

                        <li class="dropdown-item"><i class="fa-solid fa-trash-can dropdown-list-icon"></i><a href="#"
                                onclick="deleteRecordByAjax('person/main','<?php echo $params_json ?>')">Sil!</a></li>
                    </ul>

             
                </td>
            </tr>

        <?php } ?>
    </tbody>
    <tfoot>

        <tr>
            <th>id</th>
            <th>Adı Soyadı</th>
            <th>Firma Adı</th>
            <th>Proje Adı</th>
            <th>Sigorta No</th>
            <th>Telefon</th>
            <th>Adres</th>
            <th>Günlük Ücreti</th>
            <th>Durumu</th>
            <th>Email</th>
            <th>#</th>
        </tr>
    </tfoot>
</table>

<!-- /.content -->
<?php
include_once "../../plugins/datatables/datatablescripts.php" ?>


