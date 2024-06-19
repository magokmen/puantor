<?php
require_once $_SERVER["DOCUMENT_ROOT"] ."/plugins/datatables/datatable.php";
// require_once "../../include/requires.php";
//  echo "acoount_id :" . $account_id ;
$company_id = getCompanyId($account_id);

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

        $sql = $con->prepare("Select * from parameters WHERE company_id = ? ORDER BY id desc;");
        $sql->execute(array($company_id));


        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

        ?>
            <tr>
                <td>
                    <?php echo $row["id"]; ?>
                </td>
                <td>
                    <?php echo $func->getCompanyName($row["company_id"]); ?>
                </td>
                <td>
                    <?php echo $row["param_name"]; ?>
                </td>
                <td>
                    <?php echo $row["param_type"]; ?>
                </td>
                <td class="text-center">
                    <?php echo formatdDate($row["start_date"]); ?>
                </td>
                <td class="text-center">
                    <?php echo formatdDate($row["end_date"]); ?>
                </td>
                <td class="text-center">
                    <?php echo tlFormat($row["param_val"]); ?>
                </td>

                <td>
                    <?php echo $row["calc_type"] == 1 ? "Günlük Ücret" : "Saatlik Ücret"; ?>
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

                    <i class="fa-solid fa-ellipsis list-button" data-toggle="dropdown"></i>
                    <ul class="dropdown-menu">
                        <?php if (permtrue("tanımlamalarParametreGüncelle")) : ?>

                            <li class="dropdown-item edit"><i class="fa-solid fa-edit dropdown-list-icon"></i>
                                <a href="#" onclick="RoutePagewithParams('params/edit','id=<?php echo $row['id'] ?>')" data-title="Parametre Güncelleme">
                                    Güncelle
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php
                        $params = array(
                            "id" => $row["id"],
                            "message" => $row["param_name"] . ' isimli parametre silinecektir.Devam etmek istiyor musunuz?'
                        );
                        $params_json = $func->jsonEncode($params);
                        ?>
                        <?php if (permtrue("tanımlamalarParametreSil")) : ?>
                            <li class="dropdown-item">
                                <i class="fa-solid fa-trash-can dropdown-list-icon"></i>
                                <a href="#" onclick="deleteRecordByAjax('params/main','<?php echo $params_json ?>')">Sil!</a>
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
include_once $_SERVER["DOCUMENT_ROOT"] ."/plugins/datatables/datatablescripts.php" ?>