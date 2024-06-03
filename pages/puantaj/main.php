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



if (isset($_GET["company_id"])) {
    $company_id = $_GET["company_id"];
} else {

    $sql = $con->prepare("SELECT * FROM companies WHERE account_id = ? AND isDefault = ?");
    $sql->execute(array($account_id, 1));
    $result = $sql->fetch(PDO::FETCH_OBJ);
    $company_id = $result->id;
}


// $company_id = isset($_GET["company_id"]) ? $_GET["company_id"] : 0;
$project_id = isset($_GET["project_id"]) ? $_GET["project_id"] : 0;
$year = isset($_GET["year"]) ? $_GET["year"] : date('Y');
$month = isset($_GET["months"]) ? $_GET["months"] : date('m');

$days = $func->daysInMonth($month, $year);
$format = "d.m.Y";
$dates = generateDates($year, $month, $days);


$search_name = isset($_GET["search_name"]) ? $_GET["search_name"] : "";
$search_job = isset($_GET["search_job"]) ? $_GET["search_job"] : "";
$search_projects = isset($_GET["search_projects"]) ? $_GET["search_projects"] : "";

if ($search_name == null & $search_job == null & $search_projects == null) {
    $collapsed = "collapse";
} else {
    $collapsed = "";
}





//Firmaya göre kayıt yapılan personeller getirilir
if ($project_id == null) {
    $sql = $con->prepare("SELECT * FROM person WHERE company_id = ?");
    $sql->execute(array($company_id));
} else {

    // SQL sorgusunu hazırlayalım
    $sql = $con->prepare("SELECT * FROM person WHERE company_id = ? AND project_id REGEXP CONCAT('[[:<:]]', ?, '[[:>:]]')");
    $sql->execute(array($company_id, $project_id));
}

?>



<style>
    .gun {
        width: 35px;
        min-width: 35px;
        background-color: white;
        text-align: center;
        cursor: pointer;
        font-weight: 600;

    }

    .gunadi {
        width: 40px !important;
        max-width: 40px !important;

    }

    table.dataTable.table-sm>thead>tr>th:not(.sorting_disabled) {
        padding: 7px !important;
    }

    .dataTables_wrapper .dataTables_filter {
        display: none;
    }

    .table {
        padding-bottom: 15px !important;

    }

    .table tbody tr td {
        max-height: 35px !important;
        height: 35px !important;
        padding: 4px !important;
        vertical-align: middle !important;
    }

    .table tr td,
    .table th {
        border: 1px solid #ddd !important;
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

    th.ld {
        min-width: 100px;
    }

    th.vertical {
        height: 100px;
        width: 40px;
        min-width: 40px;
        max-width: 40px;
        vertical-align: bottom;
        padding: 0;
        line-height: 1.5;
        position: relative;
    }

    th.vertical span {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        width: 40px;
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

    table {
        width: 100% !important;
    }

    [data-tooltip]:before {
        text-align: left;
    }
</style>


<div class="card card-outline card-info p-3">

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


    <div id="accordion">
        <div class="card shadow-none">
            <div class="card-header">
                <div class="row d-flex justify-content-between">
                    <div class="left-btn">

                        <a data-toggle="collapse" href="#collapseOne">
                            <i class="fa-solid fa-magnifying-glass"></i>

                        </a>
                        <div class="btn ml-3">
                            <button type="button" class="btn btn-default" data-toggle="dropdown">Rapor Al <i
                                    class="fa-solid fa-caret-down ml-3"></i> </button>

                            <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item" href="#">Puantajı Excele Aktar</a>
                                <!-- <div class="dropdown-divider"></div> -->

                            </div>
                        </div>
                    </div>
                    <div class="right-btn">

                        <button type="button" id="" onclick="puantaj_olustur()" class="btn btn-primary float-right"><i
                                class="fas fa-save mr-2"></i> Kaydet</button>
                    </div>

                </div>
                <div id="collapseOne" class="<?php echo $collapsed; ?>" data-parent="#accordion">
                    <div class="row p-3">
                        <div class="col-md-3 col-sm-12 mb-3">
                            <input type="text" class="form-control" autocomplete="off" id="search_name"
                                value="<?php echo $search_name; ?>" placeholder="Adı Soyadına göre ara...">
                        </div>
                        <div class="col-md-3 col-sm-12 mb-3">
                            <input type="text" class="form-control" id="search_job" value="<?php echo $search_job; ?>"
                                placeholder="Unvanına göre ara... ">
                        </div>
                        <div class="col-md-3 col-sm-12 mb-3">
                            <input type="text" class="form-control" id="search_projects"
                                value="<?php echo $search_projects; ?>" placeholder="Projesine göre ara... ">
                        </div>
                    </div>
                </div>
            </div>


        </div>



        <div class="row pb-3">
            <div class="col-md-3 col-sm-12">
                <label for="company">Şirket <font color="red">*</font>
                    <span class="pointer" data-tooltip="İşlem yapacağınız şirketiniz" data-tooltip-location="right">
                        <i class="text-blue fa-solid fa-circle-info"></i>
                    </span>
                </label>
                <?php $func->companies("company", $company_id) ?>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="project">Proje <font color="red">*</font></label>
                <?php echo $func->projects("project", $company_id, $project_id) ?>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="months">Ay <font color="red">*</font></label>
                <?php echo $func->getMonthsSelect("months", $month) ?>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="year">Yıl <font color="red">*</font></label>
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

    </div>

    <table id="pTable" class="table table-sm table-responsive p-2 responsive-table">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th></th>

                <?php foreach ($dates as $date): ?>
                    <?php
                    $style = '';
                    if (isWeekend($date)) {
                        $style = "background-color:#99A98F;color:white";
                    }
                    echo ' <th class="gunadi" style="' . $style . '">' . $func->gunadi($date);
                    '.</th>' ?>
                <?php endforeach; ?>

            </tr>
            <tr>

                <th class="ld">Adı Soyadı</th>
                <th class="ld">Unvanı</th>
                <th class="ld">Projesi</th>
                <?php foreach ($dates as $date):
                    $style = '';
                    if (isWeekend($date)) {
                        $style = "background-color:#99A98F;color:white";
                    }
                    echo '<th class="vertical" style="' . $style . '"><span>' . $date . '</span></th>'
                        ?>

                <?php endforeach; ?>
            </tr>

        </thead>
        <tbody>
            <?php



            while ($person = $sql->fetch(PDO::FETCH_ASSOC)) {
                $job_start_date = DateTime::createFromFormat($format, $person["job_start_date"]);

                ?>
                <tr>
                    <td class="text-nowrap" style="min-width:12vw;" data-id="<?php echo $person["id"] ?>"><a
                            class="btn-user-modal" type="button">
                            <?php echo $person["full_name"] ?></a></td>

                    <td class="text-nowrap" style="min-width:10vw;">
                        <?php echo $person["job"] ?>
                    </td>


                    <?php
                    $projectNames = $func->getProjectNames($person["project_id"]);
                    ?>

                    <td class="text-nowrap" data-tooltip="<?php echo $projectNames; ?>"><?php

                       echo $func->shortProjectsName($projectNames);

                       ?></td>
                    <?php
                    foreach ($dates as $date):
                        $month_date = DateTime::createFromFormat($format, $date);
                        ?>
                        <?php

                        $puantaj_id = kayitVarmi($company_id, $person["id"], $year, $month);
                        if ($job_start_date <= $month_date) {
                            if ($puantaj_id > 0) {
                                $query = $con->prepare("SELECT * FROM puantaj where id = ?");
                                $query->execute(array($puantaj_id));
                                $puantaj_data = $query->fetch(PDO::FETCH_ASSOC);
                                $data_json = json_decode($puantaj_data["datas"], true);

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
                        } else {
                            echo "<td class='noselect text-center' style='background:#ddd'>---</td>";
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

<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="pages/puantaj/app.js"></script>
<script>
    $(document).ready(function () {

        $('.select2').select2({
            minimumResultsForSearch: Infinity
        })
        function filterWaitingDemand() {
            var table = $('#pTable').DataTable();
            table.column(5).search('Ma').draw();

        }




        var table = new DataTable('#pTable', {
            //filter: false,
            //    searching : true,
            ordering: false,
            language: {
                info: '_PAGES_ sayfadan _PAGE_. sayfa gösteriliyor',
                infoEmpty: 'Hiç kayıt bulunamadı',
                infoFiltered: '(_MAX_ kayıt filtrelendi)',
                lengthMenu: 'Her sayfada _MENU_ kayıt göster',
                zeroRecords: 'Kayıt Yok!',
                search: "Ara",
                paginate: {
                    "first": "İlk",
                    "last": "Son",
                    "next": "Sonraki",
                    "previous": "Önceki"
                },
            },
        });

        // #column3_search is a <input type="text"> element
        $('#search_name').on('keyup', function () {
            table
                .columns(0)
                .search(this.value)
                .draw();
        });

        $('#search_job').on('keyup', function () {
            table
                .columns(1)
                .search(this.value)
                .draw();
        });

        $('#search_projects').on('keyup', function () {
            table
                .columns(2)
                .search(this.value)
                .draw();
        });

    })

</script>






<!-- <script>
        $(document).ready(function () {
            function formatOption(option) {
                if (!option.id) {
                    return option.text;
                }

                var $option = $('<span>' + option.text + ' <span class="person-count"></span></span>');

                $.ajax({
                    url: '../../ajax.php',
                    type: 'POST',
                    data: {
                        id: option.id,
                        action: "person-count"
                    },
                    success: function (response) {
                        var result = JSON.parse(response);
                        var result = JSON.parse(response);
                        var countText = result.length > 0 ? result.length : 0;
                        if (result.length > 0) {
                            // Örneğin, dönen verinin sayısını göstermek istiyorsanız
                            var $countSpan = $('<span class="badge badge-danger float-right">').text(countText);
                        } else {
                            var $countSpan = $('<span class="float-right">').text(countText);
                            
                        }
                        $option.find('.person-count').append($countSpan);
                    }
                });
                return $option;
            }

            $('#company').select2({
                templateResult: formatOption,
                templateSelection: formatOption
            });
        });
    </script> -->