<?php
require_once "../../include/requires.php";

function isWeekend($date)
{
    $dateTime = new DateTime($date);
    // 'N' formatı, haftanın gününü 1 (Pazartesi) ile 7 (Pazar) arasında bir sayı olarak döndürür
    $dayOfWeek = $dateTime->format('N');
    // Haftasonu kontrolü (Cumartesi veya Pazar)
    //return ($dayOfWeek == 6 || $dayOfWeek == 7);

    // Haftasonu kontrolü (Sadece Pazar)
    return ($dayOfWeek == 7);
}
function generateDates($year, $month, $days)
{
    $dateList = [];
    for ($day = 1; $day <= $days; $day++) {
        // Tarih formatını ayarlama (d.m.Y)
        $formattedDate = sprintf("%02d.%02d.%d", $day, $month, $year);
        $dateList[] = $formattedDate;
    }
    return $dateList;
}


$company_id = isset($_GET["company_id"]) ? $_GET["company_id"] : 0;
$year = isset($_GET["year"]) ? $_GET["year"] : date('Y');
$month = isset($_GET["months"]) ? $_GET["months"] : date('m');

$days = $func->daysInMonth($month, $year);
$dates = generateDates($year, $month, $days);


//Firmaya göre kayıt yapılan personeller getirilir
$sql = $con->prepare("SELECT * FROM person where company_id = ?");
$sql->execute(array($company_id));
// echo "company_id = " . $company_id;
?>



<style>
    .gun {
        width: 40px;
        min-width: 40px;
        height: 30px;
        background-color: white;
        text-align: center;
        cursor: pointer;
    }

    .gun.clicked {
        background-color: #FFED00 !important;
        cursor: pointer;
    }

    .unclicked {
        background-color: white;
    }

    th:hover {
        cursor: pointer;
    }

    th.vertical {
        height: 100px;
        width: 40px;
        vertical-align: bottom;
        padding: 0;
        line-height: 1.5;
        position: relative;
    }

    th.vertical span {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        white-space: nowrap;
        bottom: 0;
        position: absolute;
        left: 50%;
        padding: 3px;
        transform: translateX(-50%) rotate(180deg);
    }

    .hover-menu ul li {
        padding: 15px;
        margin: 5px;
        border: none !important;

    }

    .hover-menu ul li:hover {
        background-color: #eee;
        border-radius: 6px;
        cursor: pointer;


    }

    .hover-menu .nav-item {
        padding: 7px !important;
        margin: 2px !important;

    }

    .card-body .hover-menu {
        padding: 5px !important;

    }

    .hover-menu ul li:active {
        background-color: #ccc;
        transition: background-color 0.4s ease;
        /* Geçiş efekti */
    }

    .noselect {
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }

    .avatar {

        color: white;
        opacity: 1;
        content: attr(data-initials);
        font-weight: bold;
        border-radius: 50%;
        vertical-align: middle;
        margin-right: 0.4em;
        width: 35px;
        height: 35px;
        line-height: 35px;
        text-align: center;
        float: left;
    }

    .avatar-big {
        width: 70px;
        height: 70px;
        line-height: 70px;
        color: #222;
    }

    [data-tooltip]:before {
        text-align: left;
    }
</style>
<a href="" data-tooltip="* Tüm kolonu seçmek için tarihe basınız 
 * Seçimi kaldırmak için tekrar basınız
 * Seçili alanı silmek için delete tuşuna basınız.
 * Yaptığınız değişiklikleri kaydetmeyi unutmayın" data-tooltip-location="right">

    <i class="fa-solid fa-circle-info"></i>
</a>

<div class="card card-outline card-info">

    <div class="modal fade" id="modal-default">

        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Puantaj Seç</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 col-sm-5">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist"
                                aria-orientation="vertical">
                                <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill"
                                    href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home"
                                    aria-selected="true">Normal Çalışma</a>
                                <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill"
                                    href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile"
                                    aria-selected="false">Fazla Mesai</a>
                                <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill"
                                    href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages"
                                    aria-selected="false">Ücretli İzin</a>
                                <a class="nav-link" id="vert-tabs-settings-tab" data-toggle="pill"
                                    href="#vert-tabs-settings" role="tab" aria-controls="vert-tabs-settings"
                                    aria-selected="false">Ücretsiz</a>
                            </div>
                        </div>
                        <div class="col-6 col-sm-7">
                            <div class="tab-content" id="vert-tabs-tabContent">
                                <div class="tab-pane text-left fade show active hover-menu" id="vert-tabs-home"
                                    role="tabpanel" aria-labelledby="vert-tabs-home-tab">

                                    <?php echo $func->getPuantajTuruList('Normal Çalışma'); ?>


                                </div>
                                <div class="tab-pane fade hover-menu" id="vert-tabs-profile" role="tabpanel"
                                    aria-labelledby="vert-tabs-profile-tab">

                                    <?php echo $func->getPuantajTuruList('Saatlik'); ?>

                                </div>
                                <div class="tab-pane fade hover-menu" id="vert-tabs-messages" role="tabpanel"
                                    aria-labelledby="vert-tabs-messages-tab">

                                    <?php echo $func->getPuantajTuruList('Ücretli İzin'); ?>

                                </div>
                                <div class="tab-pane fade hover-menu" id="vert-tabs-settings" role="tabpanel"
                                    aria-labelledby="vert-tabs-settings-tab">

                                    <?php echo $func->getPuantajTuruList('Ücretsiz'); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-end">

                    <button type="button" class="btn btn-default" style="width:100px" data-dismiss="modal">
                        Kapat
                    </button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- Kullanıcı Özet Bilgileri -->
    <div class="modal fade" id="modal-summary">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Özet Bilgi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">

                    <div class="card-body pt-0">

                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-header text-muted border-bottom-0">
                                <h3 class="lead-name"><b>Nicole Pearson</b></h3>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-7">
                                        <h5 class="lead-job"><b>Nicole Pearson</b></h5>
                                        <p class="text-muted text-sm"><b>About: </b> Web Designer / UX / Graphic
                                            Artist / Coffee Lover </p>
                                        <ul class="ml-4 mb-0 fa-ul text-muted">
                                            <li class="small"><span class="fa-li"><i
                                                        class="fas fa-lg fa-building"></i></span> Address: Demo
                                                Street 123, Demo City 04312, NJ</li>
                                            <li class="small"><span class="fa-li"><i
                                                        class="fas fa-lg fa-phone"></i></span> Phone #: + 800 -
                                                12 12 23 52</li>
                                        </ul>
                                    </div>
                                    <div class="col-5 text-center">
                                        <img src="../../dist/img/user1-128x128.jpg" alt="user-avatar"
                                            class="img-circle img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div>
                                    <a href="#" class="btn btn-sm bg-teal">
                                        <i class="fas fa-comments"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-primary">
                                        <i class="fas fa-user"></i> View Profile
                                    </a>


                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer justify-content-end">

                    <button type="button" class="btn btn-default" style="width:100px" data-dismiss="modal">
                        Kapat
                    </button>

                </div>


            </div>
            <!-- /.modal-content -->
        </div>
    </div>
    <!-- Kullanıcı Özet Bilgileri -->




    <div class="row">
        <div class="col-md-12">

            <button type="button" id="" onclick="puantaj_olustur()"
                class="btn btn-primary float-right m-2">Kaydet</button>


        </div>

    </div>
    <div class="row p-3">
        <div class="col-md-3 col-sm-12">
            <label for="type_name">Firma <font color="red">*</font></label>
            <?php $func->companies("company", $company_id) ?>
        </div>
        <div class="col-md-3 col-sm-12">
            <label for="type_name">Şantiye/Proje <font color="red">*</font></label>
            <select name="santiye" id="" class="select2" style="width:100%">
                <option value="1">Kızılca Şantiyesi</option>
            </select>
        </div>
        <div class="col-md-3 col-sm-12">
            <label for="type_name">Ay <font color="red">*</font></label>
            <?php echo $func->getMonthsSelect("months", $month) ?>
        </div>
        <div class="col-md-3 col-sm-12">
            <label for="type_name">Yıl <font color="red">*</font></label>
            <select name="year" id="year" class="select2 " style="width:100%">
                <?php
                $current_year = date('Y');
                for ($i = 2020; $i <= $current_year + 2; $i++) {
                    $selected = ($i == $year) ? ' selected' : ' ';
                    echo '<option ' . $selected . ' value="' . $i . '">' . $i . '</option>';
                }

                ?>

            </select>
        </div>
    </div>

    <table class="table table-bordered table-sm table-responsive p-2">
        <thead>
            <tr>

                <th>Adı Soyadı</th>
                <th>Unvanı</th>
                <?php foreach ($dates as $date): ?>
                    <th class="vertical"><span><?php echo $date; ?></span></th>
                <?php endforeach; ?>
                <!-- <?php
                for ($i = 1; $i < 31; $i++) {
                    ?>
                    <th class="vertical"><span>Header <?php echo $i; ?></span></th>
                    <?php
                }
                ?> -->
            </tr>
        </thead>
        <tbody>
            <?php

            

            while ($person = $sql->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td class="text-nowrap" style="min-width:12vw;" data-id="<?php echo $person["id"] ?>"><a
                            class="btn-user-modal" type="button">
                            <?php echo $person["full_name"] ?></a></td>
                    <td class="text-nowrap" style="min-width:10vw;"><a class="user-job" href="#">
                            <?php echo $person["job"] ?></a></td>

                    <?php
                    foreach ($dates as $date): ?>

                        <?php

                        $puantaj_id = $func->kayitVarmi($company_id, $person["id"], $year, $month);
                        if ($puantaj_id > 0) {
                            $query = $con->prepare("SELECT * FROM puantaj where id = ?");
                            $query->execute(array($puantaj_id));
                            $puantaj_data = $query->fetch(PDO::FETCH_ASSOC);
                            $data_json = json_decode($puantaj_data["datas"], true);
                            //$keys = array_keys($data_json);
                            //print_r($keys);
                            // foreach ($data_json as $date => $value) {
                            //     if ($value !== "") {
                            //        // echo "Tarih: $date, Değer: $value\n";
                            //         echo "<td class='gun noselect' data-id=''>".$value."</td>";
                            //     }else{
                            //     }
                            // }
                            $value = isset($data_json[$date]) ? $data_json[$date] : "0";
                            $func->puantajClass($value);
                            // echo "<td class='gun noselect' data-id=''>" . $value . "</td>";
                
                        } else {
                            if (isWeekend($date)) {
                                $func->puantajClass(6);
                            } else {
                                echo "<td class='gun noselect'></td>";
                            }

                        }

                        ?>

                    <?php endforeach; ?>

                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>


</div>


<script src="pages/puantaj/app.js"></script>
<script>
    $(document).ready(function () {

        $('.select2').select2({
            minimumResultsForSearch: Infinity
        })

        
    })
      
</script>

