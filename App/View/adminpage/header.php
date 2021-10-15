<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?= $title ?></title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?= $this->temp->public?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?= $this->temp->public?>plugins/node-waves/waves.min.css" rel="stylesheet" />

    <!-- Sweetalert Css -->
    <link href="<?= $this->temp->public?>plugins/sweetalert/sweetalert.css" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="<?= $this->temp->public?>plugins/animate-css/animate.min.css" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="<?= $this->temp->public?>plugins/morrisjs/morris.css" rel="stylesheet" />

    <link href="<?= $this->temp->public?>plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    <!-- Bootstrap Select Css -->
    <link href="<?= $this->temp->public?>plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <!-- JQuery DataTable Css -->
    <link href="<?= $this->temp->public?>plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Custom Css -->
    <link href="<?= $this->temp->public?>css/style.css" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?= $this->temp->public?>css/themes/all-themes.css" rel="stylesheet" />
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
    <script>
        var admin_id = <?= $_SESSION["id"]?>;
        var admin_username = "<?= $_SESSION['username'] ?>";
    </script>
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
                <a class="navbar-brand">RBB Admin</a>
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
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$_SESSION['username']?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="admin/logout"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MENU</li>
                    <li>
                        <a href="<?=$this->req->urlmain?>">
                            <i class="material-icons">home</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">assignment</i>
                            <span>Master</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="<?=$this->req->urlmain?>user">
                                    <span>User</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=$this->req->urlmain?>withdraw">
                                    <span>Withdraw</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=$this->req->urlmain?>user/company">
                                    <span>Company Account</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?=$this->req->urlmain?>user/nonactive">
                                    <span>Nonactive User</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?=$this->req->urlmain?>admin/history">
                            <i class="material-icons">history</i>
                            <span>History</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?=$this->req->urlmain?>admin/config">
                            <i class="material-icons">settings</i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li>
                        <a id="logoutbtn">
                            <i class="material-icons">logout</i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>