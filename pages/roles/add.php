<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once $_SERVER["DOCUMENT_ROOT"] ."/include/requires.php";

if ($_POST && $_POST['method'] == "add") {
    // Verileri al
    $authorityName = $_POST['authorityName'];
    $explanation = $_POST['explanation'];

    
    //kullanıcı var mı kontrol et
    $query = "SELECT roleName FROM userroles WHERE roleName = ?";
    $statement = $con->prepare($query);
    $statement->execute(array($authorityName));
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // Veritabanına ekleme işlemini gerçekleştir
    if ($authorityName != null && $result === false) {
        $up = $con->prepare("INSERT INTO userroles SET account_id = ?,
                                                        roleName = ? , 
                                                        roleDescription = ? ,
                                                        isActive = ? ");
        $result = $up->execute(array($account_id, $authorityName, $explanation, 1));

    }
}

?>
<form id="myForm">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <label for="authorityName">Yetki Adı<font color="red">*</font></label>
            <input type="text" required class="form-control" id="authorityName" name="authorityName" value=""
                placeholder="Yetki Adı Giriniz">
        </div>
        <div class="col-md-6 col-sm-12">
            <label for="explanation">Açıklama <font color="red">*</font></label>
            <input type="text" required class="form-control" id="explanation" name="explanation" value=""
                placeholder="Yetki Açıklaması Giriniz">
        </div>
    </div>
    <br>

</form>
