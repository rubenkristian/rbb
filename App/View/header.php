<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>KUMIS</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?= $this->obj->temp->public?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?= $this->obj->temp->public?>plugins/node-waves/waves.min.css" rel="stylesheet" />

    <!-- Sweetalert Css -->
    <link href="../../plugins/sweetalert/sweetalert.css" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="<?= $this->obj->temp->public?>plugins/animate-css/animate.min.css" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="<?= $this->obj->temp->public?>plugins/morrisjs/morris.css" rel="stylesheet" />

    <link href="<?= $this->obj->temp->public?>plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    <!-- Bootstrap Select Css -->
    <link href="<?= $this->obj->temp->public?>plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="<?= $this->obj->temp->public?>plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Custom Css -->
    <link href="<?= $this->obj->temp->public?>css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?= $this->obj->temp->public?>css/themes/all-themes.css" rel="stylesheet" />
    <style>
        .autocomplete-suggestions {
            border: 1px solid #999;
            background: #FFF;
            overflow: auto;
        }
        .autocomplete-suggestion {
            padding: 2px 5px;
            white-space: nowrap;
            overflow: hidden;
        }
        .autocomplete-selected {
            background: #F0F0F0;
        }
        .autocomplete-suggestions strong {
            font-weight: normal;
            color: #3399FF;
        }
        .autocomplete-group {
            padding: 2px 5px;
        }
        .autocomplete-group strong {
            display: block;
            border-bottom: 1px solid #000;
        }
    </style>
</head>

<body class="theme-blue">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand">Absensi Online</a>
				<!--<a class="navbar-brand">| KA</a>-->
            </div>
        </div>
    </nav>
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$_SESSION['fullname']?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="/logout"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MENU</li>
                    <li class="<?= $page_id === 12 ? 'active' : '' ?>">
                        <a href="<?=$this->obj->req->urlmain?>">
                            <i class="material-icons">home</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <?php if($_SESSION['level'] == 1 || $_SESSION['level'] == 2 || $_SESSION['level'] == 4):?>
                    <li class="<?= $page_id === 11 ? 'active' : '' ?>">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">assignment</i>
                            <span>Laporan</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>report/abs">
                                    <span>Laporan Absensi Rate</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>report/month">
                                    <span>Laporan Absensi Bulanan</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>report/Manpower">
                                    <span>Laporan MP</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif ?>
                    <?php if($_SESSION['level'] == 1 || $_SESSION['level'] == 2):?>
                    <li class="<?= $page_id === 10 ? 'active' : '' ?>">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">people</i>
                            <span>Karyawan</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>member">
                                    <span>Karyawan</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>member/inputMember">
                                    <span>Input Karyawan</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>member/inputDataResign">
                                    <span>Input Resign</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>member/importKaryawan"><span>Import Karyawan</span></a>
                            </li>
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>member/account">
                                    <span>Account</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif ?>
                    <?php if($_SESSION['level'] == 1 || $_SESSION['level'] == 3):?>
                    <li class="<?= $page_id === 13 ? 'active' : '' ?>">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">pie_chart</i>
                            <span>ABS Rate</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>abs/inputAbsensi">
                                    <span>Input ABS Rate</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>abs/historyAbsensi">
                                    <span>List History Input</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>abs/inputAbs">
                                    <span>Input Absensi Karyawan</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif ?>
                    <?php if($_SESSION['level'] == 1):?>
                    <li class="<?= $page_id === 15 ? 'active' : '' ?>">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">person</i>
                            <span>User</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>user">
                                    <span>Lihat User</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>user/inputUser">
                                    <span>Tambah User</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif ?>
                    <?php if($_SESSION['level'] == 1):?>
                    <li class="<?= $page_id === 14 ? 'active' : '' ?>">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">person</i>
                            <span>Area</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>area">
                                    <span>List Area</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=$this->obj->req->urlmain?>area/createArea">
                                    <span>Tambah Area</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif ?>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>