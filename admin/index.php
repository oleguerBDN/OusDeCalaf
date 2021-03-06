<?php
require_once '../includes/general_settings.php';
require_once 'functions_display.php';
unset($_SESSION['admin']);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- start: Meta -->
        <meta charset="utf-8">
        <title>ACME Dashboard - Perfect Bootstrap Admin Template</title>
        <meta name="description" content="ACME Dashboard Bootstrap Admin Template.">
        <meta name="author" content="Å�ukasz Holeczek">
        <meta name="keyword" content="ACME, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
        <!-- end: Meta -->

        <!-- start: Mobile Specific -->
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <!-- end: Mobile Specific -->
        <?= headIncludes($headIncludes) ?>
        <!-- start: Favicon -->
        <link rel="shortcut icon" href="img/favicon.ico">
        <!-- end: Favicon -->

        <style type="text/css">
            body { background: url(img/bg-login.jpg) !important; }
        </style>



    </head>

    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12" style="display: none">
                    <div class="alert alert-error"></div>
                </div>
                <div class="row-fluid">
                    <div class="login-box">
                        <div class="icons">
                            <a href="<?= BASE_URL ?>"><i class="halflings-icon home"></i></a>
                            <a href="#"><i class="halflings-icon cog"></i></a>
                        </div>
                        <h2>Login to your account</h2>
                        <form class="form-horizontal" action="" method="post">
                            <fieldset>

                                <div class="input-prepend" title="Username">
                                    <span class="add-on"><i class="halflings-icon user"></i></span>
                                    <input class="input-large span10" name="username" id="username" type="text" placeholder="username"/>
                                </div>
                                <div class="clearfix"></div>

                                <div class="input-prepend" title="Password">
                                    <span class="add-on"><i class="halflings-icon lock"></i></span>
                                    <input class="input-large span10" name="password" id="password" type="password" placeholder="password"/>
                                </div>
                                <div class="clearfix"></div>

                                <div class="button-login">	
                                    <button type="button" onclick="loginAdmin();" class="btn btn-primary">Login</button>
                                </div>
                                <div class="clearfix"></div>
                        </form>
                        <!--<hr>
                        <h3>Forgot Password?</h3>
                        <p>
                                No problem, <a href="#">click here</a> to get a new password.
                        </p>-->	
                    </div><!--/span-->
                </div><!--/row-->

            </div><!--/fluid-row-->

        </div><!--/.fluid-container-->

        <!-- start: JavaScript-->
        <?= footerIncludes($footerIncludes) ?>
        <!-- end: JavaScript-->

    </body>
</html>
