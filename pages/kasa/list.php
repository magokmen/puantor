<?php
require_once "../../plugins/datatables/datatable.php";
require_once "../../include/requires.php";


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
        <button type="button" class="btn btn-default">Pdf</button>
        <button type="button" class="btn btn-default">XLS</button>
        <button type="button" class="btn btn-default">Hakediş Listesi</button>
    </div>

</div>
<!-- Main content -->

<table id="example1" class="data-table table table-bordered table-striped table-sm table-hover">
    <thead>

        <tr>
            <th>id</th>
            <th>Hesap Adı</th>
            <th>Tür</th>
            <th>Tarih</th>
            <th>Kasa</th>
            <th>Kategori</th>
            <th>Tutar</th>
            <th>Açıklama</th>
           

        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>

        <tr>
            <th>id</th>
            <th>Hesap Adı</th>
            <th>Tür</th>
            <th>Tarih</th>
            <th>Kasa</th>
            <th>Kategori</th>
            <th>Tutar</th>
            <th>Açıklama</th>
          


        </tr>
    </tfoot>
</table>

<!-- /.content -->
<?php
require_once "../../plugins/datatables/datatablescripts.php";
