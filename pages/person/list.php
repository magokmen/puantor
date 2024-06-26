<?php
require_once $_SERVER["DOCUMENT_ROOT"] ."/plugins/datatables/datatable.php";

if (isset($_GET["company_id"])) {
    $company_id = $_GET["company_id"];
} else  if (isset($_SESSION["companyID"])) {
    $company_id = $_SESSION["companyID"];
} else {
    $sql = $con->prepare("SELECT * FROM companies WHERE account_id = ? AND isDefault = ?");
    $sql->execute(array($account_id, 1));
    $result = $sql->fetch(PDO::FETCH_OBJ);
    $company_id = $result->id ?? 0;
}

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
                                WHERE c.id = ?
                                ORDER BY p.id desc");
        $sql->execute(array($company_id));
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

                <td class="text-center">
                    <?php echo tlformat($value["daily_wages"]) . " TL" ?>
                </td>

                <td>
                    <?php echo $value["state"] ?>
                </td>

                <td>
                    <?php echo $value["email"] ?>
                </td>

                <td class="">
      
                <i class="fa-solid fa-ellipsis list-button" data-toggle="dropdown"></i>
                  
                    <ul class="dropdown-menu">
                        <?php if (permtrue("personelGüncelle")): ?>
                            <li class="dropdown-item"><i class="fa-solid fa-edit dropdown-list-icon"></i>
                                <a  href="#" onclick="RoutePagewithParams('person/edit','id=<?php echo $value['id'] ?>')"
                                    data-title="Personel Güncelleme">
                                    Güncelle
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (permtrue("personelPuantajListesi")): ?>
                            <li class="dropdown-item"><i class="fa-solid fa-calendar-days dropdown-list-icon"></i><a href="#"
                                    onclick="RoutePage('offers/edit', this)" data-params="id=<?php echo $value["id"] ?>"
                                    data-title="Puantaj Listesi">
                                    Puantaj listesi
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (permtrue("personelGelirEkle")): ?>
                            <li class="dropdown-item"><i class="fa-solid fa-wallet dropdown-list-icon"></i><a
                                    href="#" onclick="RoutePage('persone/income', this)"
                                    data-params="id=<?php echo $value["id"] ?>" data-title="Gelir Ekle">
                                    Gelir Ekle
                                </a>
                            </li>

                        <?php endif; ?>
                        <?php if (permtrue("personelKesintiEkle")): ?>
                            <li class="dropdown-item"><i class="fa-regular fa-thumbs-down dropdown-list-icon"></i><a
                                    href="#" onclick="RoutePage('persone/deduction', this)"
                                    data-params="id=<?php echo $value["id"] ?>" data-title="Kesinti Ekle">
                                    Kesinti Ekle
                                </a>
                            </li>

                        <?php endif; ?>
                        <?php if (permtrue("personelHesapHareketleri")): ?>

                            <li class="dropdown-item"><i class="fa-solid fa-file-pen dropdown-list-icon"></i><a href="#">Hesap
                                    Hareketleri
                                </a>
                            </li><?php endif; ?>

                        <li class="dropdown-divider"></li>

                        <?php
                        $params = array(
                            "id" => $value["id"],
                            "message" => $value["full_name"] . ' isimli personel silinecektir.Devam etmek istiyor musunuz?'
                        );
                        $params_json = $func->jsonEncode($params);
                        ?>
                        <?php if (permtrue("personelSil")): ?>
                            <li class="dropdown-item"><i class="fa-solid fa-trash-can dropdown-list-icon"></i><a href="#"
                                    onclick="deleteRecordByAjax('person/main','<?php echo $params_json ?>')">Sil!</a>
                            </li>
                            <?php endif; ?>
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
include_once $_SERVER["DOCUMENT_ROOT"] ."/plugins/datatables/datatablescripts.php" ?>