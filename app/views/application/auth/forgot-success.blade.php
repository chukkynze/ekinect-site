<?php
/**
 * filename:   login.php
 *
 * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
 * @since       7/13/14 1:27 AM
 *
 * @copyright   Copyright (c) 2014 www.eKinect.com
 */
?>

<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,IE=9,IE=8,chrome=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>EKinect - Member Access</title>

    <!--[if lt IE 9]><script type="text/javascript" src="/auth/theme/js/flot/excanvas.min.js"></script><![endif]-->
    <!--[if lt IE 9]><script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <!--[if lt IE 9]><script type="text/javascript" src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->

    <link href="/auth/theme/css/cloud-admin.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/auth/theme/font-awesome/css/font-awesome.min.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/auth/theme/js/bootstrap-daterangepicker/daterangepicker-bs3.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/auth/theme/js/uniform/css/uniform.default.min.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/auth/theme/css/animatecss/animate.min.css" media="screen" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" media="screen" rel="stylesheet" type="text/css">
    <link href="/auth/views/css/login.css" media="screen" rel="stylesheet" type="text/css">
    <link href="/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

</head>
<body class="login">
	<!-- PAGE -->
	<section id="page">
			<!-- HEADER -->
			<header>
				<!-- NAV-BAR -->
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div id="logo">
                                <a href="/"><img src="/app/images/site_images/logo.png" height="60" alt="logo name" /></a>
							</div>
						</div>
					</div>
				</div>
				<!--/NAV-BAR -->
			</header>
			<!--/HEADER -->
			<!-- FORGOT PASSWORD -->
			<section id="forgot" class="visible">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div class="login-box-plain">
								<h2 class="bigintro">Request Submitted</h2>
								<div class="center">
									<strong>
										Once we find your profile, we will send an email with instructions on how to access your account.<br>
										Thank You.
									</strong>
								</div>

							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- FORGOT PASSWORD -->
	</section>
	<!--/PAGE -->

    <script type="text/javascript" src="/auth/theme/js/jquery/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/auth/theme/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="/auth/theme/bootstrap-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/auth/theme/js/uniform/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="/auth/theme/js/script.js"></script>
    <!--[if lt IE 9]><script type="text/javascript" src="/auth/theme/js/flot/excanvas.min.js"></script><![endif]-->
    <!--[if lt IE 9]><script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <!--[if lt IE 9]><script type="text/javascript" src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->


    <script>
        jQuery(document).ready(function() {
            App.setPage("login");  //Set current page
            App.init(); //Initialise plugins and elements
        });
    </script>
    <script type="text/javascript">
        function swapScreen(id) {
            jQuery('.visible').removeClass('visible animated fadeInUp');
            jQuery('#'+id).addClass('visible animated fadeInUp');
        }
    </script>
    <!-- /JAVASCRIPTS -->
</body>
</html>