<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sign In</title>
    <!-- Favicon-->
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <link href="<?= $this->temp->public?>plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $this->temp->public?>plugins/node-waves/waves.min.css" rel="stylesheet">
    <link href="<?= $this->temp->public?>plugins/animate-css/animate.min.css" rel="stylesheet">
    <link href="<?= $this->temp->public?>css/style.css" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);"><b>RBB ADMIN</b></a>
        </div>
        <div class="card">
            <div class="body">
                <form action="<?=$this->temp->url?>/authentication" id="login" name="login" id="sign_in" method="POST">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line">
                            <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                        </div>
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN</button>
						</div>
                    </div>
                </form>
				<div class="msg"></div>
            </div>
        </div>
    </div>
    <script src="<?= $this->temp->public?>plugins/jquery/jquery.min.js"></script>
    <script src="<?= $this->temp->public?>plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= $this->temp->public?>plugins/node-waves/waves.min.js"></script>
    <script src="<?= $this->temp->public?>plugins/jquery-validation/jquery.validate.js"></script>
    <script src="<?= $this->temp->public?>js/admin.min.js"></script>
    <script src="<?= $this->temp->public?>js/pages/examples/sign-in.js"></script>
    <!-- WhatsHelp.io widget -->
    <script type="text/javascript">
        (function () {
            var options = {
                whatsapp: "+6285939843889", // WhatsApp number
                call_to_action: "Call me!", // Call to action
                position: "right", // Position may be 'right' or 'left'
            };
            var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
            s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
            var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
        })();
    </script>
    <!-- /WhatsHelp.io widget -->
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
      (adsbygoogle = window.adsbygoogle || []).push({
        google_ad_client: "ca-pub-1796988898153196",
        enable_page_level_ads: true
      });
    </script>
</body>

</html>