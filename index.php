<?php require_once "include/requires.php";

if (!isset($_SESSION['login']) || !isset($_SESSION["accountID"])) {
    header("Location: logout.php");
    exit;
}



if ($expired_date < 3) {
    $badgestyle = "danger";
} else if ($expired_date < 6) {
    $badgestyle = "warning";
} else if ($expired_date > 6) {
    $badgestyle = "info";
}

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="src/css/style.css">
    <script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="src/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="src/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="src/img/favicon-16x16.png">
    <link rel="manifest" href="src/img/site.webmanifest">
    <link rel="canonical" href="https://puantor.mbeyazilim.com" />
    <!-- <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.38.0/js/tempusdominus-bootstrap-4.min.js"
        crossorigin="anonymous"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.38.0/css/tempusdominus-bootstrap-4.min.css"
        crossorigin="anonymous" />   -->

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <script src="https://kit.fontawesome.com/0c7584bba4.js" crossorigin="anonymous"></script>
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <!-- <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css"> -->
    <!-- Theme style -->
    <!-- <link rel="stylesheet" href="dist/css/adminlte.min.css"> -->

    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- overlayScrollbars -->
    <!-- <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css"> -->
    <!-- Daterange picker -->
    <!-- <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css"> -->
    <!-- summernote -->
    <!-- <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css"> -->
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">


    <!-- Select2 -->
    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Toastr
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css"> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <title>Puantor | Puantaj Takip Uygulaması</title>
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
        rel="stylesheet">


</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed">

    <div class="wrapper">


        <!-- Preloader-->
        <!-- <div class="preloader">
            <div class="main-loader">
                <div class="loader">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div> -->


        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light d-flex justify-content-between">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a class="nav-link loadContent" href="#" data-page="index" data-title="Ana Sayfa">Puantor |
                        Puantaj
                        Takip </a>
                </li>

            </ul>
            <div class="flex-grow">
                <?php if ($user_state != 1) { ?>
                    <marquee behavior="" direction="rihgt" onmouseover="stop()" onmouseout="start()">
                        <span class="text-blue">
                            Deneme sürümünün bitmesine kalan süre <span
                                class="badge badge-<?php echo $badgestyle; ?> p-2"><?php echo $expired_date; ?></span>
                            gün.Kullanmaya devam etmek için lütfen satın alın
                        </span>
                    </marquee>
                <?php }
                ; ?>
            </div>

            <!-- Right navbar links -->
            <ul class="navbar-nav ">
                <li class="nav-item">
                    <?php

                    if (isset($_GET["company_id"])) {
                        $company_id = $_GET["company_id"];
                        $_SESSION["companyID"] = $company_id;
                    } else {
                        if (isset($_SESSION["companyID"])) {
                            $company_id = $_SESSION["companyID"];
                        } else {

                            $sql = $con->prepare("SELECT * FROM companies WHERE account_id = ? AND isDefault = ?");
                            $sql->execute(array($account_id, 1));
                            $result = $sql->fetch(PDO::FETCH_OBJ);
                            $company_id = $result->id ?? 0;
                        }
                    }


                    echo $func->companies("companyindex", $company_id, "250px");
                    // $companyName = $func->getCompanyName($company_id);
                    // $convertedName = convertTurkishCharacters($companyName);
                    
                    ?>

                    <script>
                        history.pushState(null, '', '/');
                    </script>
                </li>
                <!-- Navbar Search -->
                <li class="nav-item">


                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header"><?php echo "account : ". $account_id ;?></span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

            </ul>

        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="logout.php" class="brand-link">
                <img src="../src/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                <span
                    class="brand-text font-weight-light"><?php echo $func->getCompanyName($_SESSION["companyID"]); ?></span>
            </a>



            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="../src/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">
                            <?php echo $_SESSION['UserFullName']; ?>
                        </a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                        <?php
                        if (!isset($_SESSION["activeLink"])) {
                            $_SESSION["activeLink"] = "Ana Sayfa";
                        }

                        $pageTitle = "Ana Sayfa";
                        $active = $_SESSION["activeLink"] == $pageTitle ? "active" : ""; ?>
                        <li class="nav-item">
                            <a class="nav-link nav-menu loadContent <?php echo $active; ?>" href="#" data-page="index"
                                data-title="<?php echo $pageTitle; ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    <?php echo $pageTitle; ?>
                                </p>
                            </a>
                        </li>

                        <?php if (permtrue("puantaj")): ?>
                            <?php
                            $pageTitle = "Puantaj";

                            ?>
                            <li class="nav-item">
                                <a class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>" href="#"
                                    data-page="puantaj/main" data-title="<?php echo $pageTitle; ?>">
                                    <i class="nav-icon fa-regular fa-calendar-days"></i>
                                    <p>
                                        <?php echo $pageTitle; ?>
                                    </p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (permtrue("bordro")): ?>
                            <?php $pageTitle = "Bordro"; ?>
                            <li class="nav-item ">
                                <a class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>" href="#"
                                    data-page="bordro/main" data-title="<?php echo $pageTitle; ?>">
                                    <i class="nav-icon fa-solid fa-money-bills"></i>
                                    <p>
                                        <?php echo $pageTitle; ?>
                                    </p>
                                </a>
                            </li>
                            <?php endif; ?>

                        <?php if (permtrue("personel")): ?>
                            <?php
                            $pageTitle = "Personeller";
                            if ($_SESSION["activeLink"] == "Personel Listesi" || $_SESSION["activeLink"] == "Yeni Personel") {
                                $active = "active";
                                $menu_open = "menu-is-opening menu-open";
                            } else {
                                $active = "";
                                $menu_open = "";
                            }
                            ?>
                            <li class="nav-item <?php echo $menu_open; ?>">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-user"></i>
                                    <p>
                                        <?php echo $pageTitle; ?>
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if (permtrue("personelYeni")): ?>
                                        <?php $pageTitle = "Yeni Personel"; ?>
                                        <li class="nav-item">
                                            <a href="#" data-page="person/main" data-title="Yeni Personel"
                                                class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>
                                                    <?php echo $pageTitle; ?>
                                                    <span class="right badge badge-danger">Yeni</span>
                                                </p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (permtrue("personelListesi")): ?>
                                        <?php $pageTitle = "Personel Listesi"; ?>
                                        <li class="nav-item">
                                            <a href="#" data-page="person/main" data-title="Personel Listesi"
                                                class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <?php echo $pageTitle; ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <?php if (permtrue("proje")): ?>
                            <?php
                            $pageTitle = "Proje Takip";
                            if ($_SESSION["activeLink"] == "Alınan Projeler" || $_SESSION["activeLink"] == "Verilen Projeler") {
                                $active = "active";
                                $menu_open = "menu-is-opening menu-open";
                            } else {
                                $active = "";
                                $menu_open = "";
                            }
                            ?>
                            <li class="nav-item <?php echo $menu_open; ?>">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fa-regular fa-building"></i>
                                    <p>
                                        Proje Takip
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if (permtrue("alinanProjeler")): ?>
                                        <?php $pageTitle = "Alınan Projeler"; ?>
                                        <li class="nav-item">
                                            <a href="#" data-page="projects/main" data-params="type=1"
                                                data-title="<?php echo $pageTitle; ?>"
                                                class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>
                                                    <?php echo $pageTitle; ?>
                                                    <span class="right badge badge-danger">Yeni</span>
                                                </p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (permtrue("verilenProjeler")): ?>
                                        <?php $pageTitle = "Verilen Projeler"; ?>
                                        <li class="nav-item">
                                            <a href="#" data-page="projects/main" data-params="type=2"
                                                data-title="<?php echo $pageTitle; ?>"
                                                class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p><?php echo $pageTitle; ?></p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <?php if (permtrue("firmalar")): ?>
                            <?php $pageTitle = "Firmalar"; ?>
                            <li class="nav-item">
                                <a class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>" href="#"
                                    data-page="firms/main" data-title="Firma Listesi">
                                    <i class="nav-icon fa-regular fa-folder-open"></i>
                                    <p>
                                        <?php echo $pageTitle; ?>
                                    </p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (permtrue("kasa")): ?>
                            <?php $pageTitle = "Kasa"; ?>
                            <li class="nav-item">
                                <a class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>" href="#"
                                    data-page="kasa/main" data-title="<?php echo $pageTitle; ?>">
                                    <i class="nav-icon fa-solid fa-building-columns"></i>
                                    <p>
                                        <?php echo $pageTitle; ?>
                                    </p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (permtrue("tanimlamalar")): ?>
                            <?php
                            $pageTitle = "Tanımlamalar";
                            if ($_SESSION["activeLink"] == "Parametre Tanımlama" || $_SESSION["activeLink"] == "Kesinti Türü Listesi") {
                                $active = "active";
                                $menu_open = "menu-is-opening menu-open";
                            } else {
                                $active = "";
                                $menu_open = "";
                            }
                            ?>
                            <li class="nav-item <?php echo $menu_open; ?>">
                                <a href="#" class="nav-link" data-page="Tanımlamalar"
                                    data-title="<?php echo $pageTitle; ?>">
                                    <i class="nav-icon fa-solid fa-gears"></i>
                                    <p>
                                        <?php echo $pageTitle; ?>
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if (permtrue("tanımlamalarParametreEkle")): ?>
                                        <?php $pageTitle = "Parametre Tanımlama"; ?>
                                        <li class="nav-item">
                                            <a href="#" data-page="params/main" data-title="<?php echo $pageTitle; ?>"
                                                class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p><?php echo $pageTitle; ?></p>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (permtrue("tanımlamalarKesintiListesi")): ?>
                                        <?php $pageTitle = "Kesinti Türü Listesi"; ?>
                                        <li class="nav-item">
                                            <a href="#" data-page="defines/cuts/main" data-title="<?php echo $pageTitle; ?>"
                                                class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Kesinti Türü Tanımlama</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>


                        <?php if (permtrue("şirketlerim")): ?>
                            <?php $pageTitle = "Şirketlerim"; ?>
                            <li class="nav-item">
                                <a href="#" data-page="company/main" data-title="Şirket Listesi"
                                    class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>">
                                    <i class="fa-solid fa-folder-tree nav-icon"></i>
                                    <p><?php echo $pageTitle; ?></p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (permtrue("kullanıcılar")): ?>
                            <?php
                            $pageTitle = "Kullanıcılar";
                            if ($_SESSION["activeLink"] == "Kullanıcı Listesi" || $_SESSION["activeLink"] == "Yeni Kullanıcı") {
                                $active = "active";
                                $menu_open = "menu-is-opening menu-open";
                            } else {
                                $active = "";
                                $menu_open = "";
                            }
                            ?>
                            <li class="nav-item <?php echo $menu_open; ?>">
                                <a href="#" class="nav-link" data-page="kullanıcılar"
                                    data-title="<?php echo $pageTitle; ?>">
                                    <i class="nav-icon fa-solid fa-users"></i>
                                    <p>
                                        <?php echo $pageTitle; ?>
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if (permtrue("kullanıcılarYeni")): ?>
                                        <?php $pageTitle = "Yeni Kullanıcı"; ?>
                                        <li class="nav-item">
                                            <a href="#" data-page="users/main" data-title="<?php echo $pageTitle; ?>"
                                                class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p><?php echo $pageTitle; ?></p>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if (permtrue("kullanıcıListele")): ?>
                                        <?php $pageTitle = "Kullanıcı Listesi"; ?>
                                        <li class="nav-item">
                                            <a href="#" data-page="users/main" data-title="<?php echo $pageTitle; ?>"
                                                class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p><?php echo $pageTitle; ?></p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>



                        <?php if (permtrue("yetkiler")): ?>
                            <?php $pageTitle = "Yetkiler"; ?>
                            <li class="nav-item">
                                <a class="nav-link nav-menu loadContent <?php echo getActiveStatus($pageTitle); ?>" href="#"
                                    data-page="roles/main" data-title="Yetki Grupları">
                                    <i class="nav-icon fa-solid fa-lock-open"></i>
                                    <p>
                                        <?php echo $pageTitle; ?>
                                    </p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (permtrue("ayarlar")): ?>
                            <li class="nav-item">
                                <a class="nav-link nav-menu loadContent" href="#" data-page="settings" data-title="Ayarlar">
                                    <i class="nav-icon fa-solid fa-gear"></i>
                                    <p>
                                        Ayarlar
                                    </p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($_SESSION["accountType"] == 1): ?>
                            <li class="nav-item">
                                <a class="nav-link nav-menu loadContent" href="#" data-page="accounts/list"
                                    data-title="Hesaplar">
                                    <i class="nav-icon  fa-solid fa-user-gear"></i>
                                    <p>
                                        Hesaplar
                                    </p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link nav-menu" href="logout.php" data-page="">
                                <i class="nav-icon fa-solid fa-right-from-bracket"></i>
                                <p>
                                    Çıkış yap
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0" id="page-title"> <?php
                            $pageTitle = $_SESSION["pageTitle"] ?? "Ana Sayfa";
                            echo $pageTitle; ?>
                            </h1>
                        </div><!-- /.col -->

                    </div><!-- /.row -->

                    <div id="content" class="maincontent">
                        <?php

                        if (isset($_SESSION["page"])) {
                            require_once $_SESSION["page"];
                        } else {
                            require_once "index.php";
                        }
                        ?>
                    </div>
                </div><!-- /.container-fluid -->

            </div>
            <!-- /.content-header -->




        </div>

        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Puantor | Puantaj Uygulaması </strong>

            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 0.0.1
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"> -->
    </script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"> -->
    </script>
    <script src="src/app.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="plugins/toastr/toastr.min.js"></script>
    <script src="plugins/popper/umd/popper.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

    <!-- Bootstrap Switch -->
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>


    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/moment/locales.min.js"></script>

    <!-- <script src="plugins/daterangepicker/daterangepicker.js"></script> -->
    <script src="plugins/inputmask/jquery.inputmask.min.js"></script>

    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- jquery-validation -->
    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="plugins/jquery-validation/additional-methods.min.js"></script>

    <!-- Select2 -->
    <script src="plugins/select2/js/select2.full.min.js"></script>


    <script src="dist/js/adminlte.js"></script>

    <!-- <script src="dist/js/pages/dashboard.js"></script> -->

</body>

</html>