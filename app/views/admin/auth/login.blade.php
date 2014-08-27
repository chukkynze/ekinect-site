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
								<a href="/admin/login"><img src="/app/images/site_images/logo.png" height="60" alt="logo name" /></a>
							</div>
						</div>
					</div>
				</div>
				<!--/NAV-BAR -->
			</header>
			<!--/HEADER -->
			<!-- LOGIN -->
			<section id="login" class="<?php echo ($activity == 'login' ? 'visible' : '' ); ?>">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div class="login-box-plain">
								<h2 class="bigintro">Employee Login</h2>
								<h3 class="center">
									<?php
										switch($LoginHeaderMessage)
										{
											case 1	:	echo "Your Member Session Expired. Please, login again to get back to your...stuff.";
														break;
											case 2	:	echo "Your Have Successfully Logged Out<br>We'll leave the light on for when you get back!";
														break;
											case 3	:	echo "Your Have Successfully Updated Your Access Credentials<br>Open Sesame!";
														break;

											default : echo "";
										}
									?>
								</h3>

                                <?php if( $LoginFormMessages != '' || $LoginAttemptMessages != ''): ?>
                                <div class="alert alert-block alert-danger fade in">

                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                    <h4><i class="fa fa-times"></i> Oh snap! You got an error!</h4>

                                    <?php if( count($LoginFormMessages) >= 1 ): ?>
                                    <ul>
                                        <?php foreach($LoginFormMessages as $LoginFormMessage): ?>
                                        <li><?php echo $LoginFormMessage; ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                    <?php endif; ?>

                                    <?php if( count($LoginAttemptMessages) >= 1 ): ?>
                                    <ul>
                                         <?php foreach($LoginAttemptMessages as $LoginAttemptMessage): ?>
                                        <li><?php echo $LoginAttemptMessage; ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                    <?php endif; ?>

                                </div>
                                <?php endif; ?>


								<div class="divide-40"></div>
                                    {{ Form::open(array('action' => 'AuthController@showAccess')) }}

                                    <?php echo Form::text('usr'         , null, array('class' => "siteInput Input1")); ?>
                                    <?php echo Form::text('username'    , null, array('class' => "siteInput Input2")); ?>
                                    <?php echo Form::text('email'       , null, array('class' => "siteInput Input3")); ?>
                                    <?php echo Form::text('login_email' , null, array('class' => "siteInput Input4")); ?>

                                        <div class="form-group">
                                            <?php echo Form::label('returning_member', 'E-Mail Address'); ?>
                                            <i class="fa fa-envelope"></i>
                                            <?php echo Form::text('returning_member', null, array('class' => "form-control")); ?>
                                        </div>
                                        <div class="form-group">
                                            <?php echo Form::label('LoginFormPasswordField', 'Password'); ?>
                                            <i class="fa fa-lock"></i>
                                            <?php echo Form::password('LoginFormPasswordField', array('class' => "form-control")); ?>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" class="btn-lg btn-danger">Login</button>
                                        </div>

                                    {{ Form::close() }}
								<div class="login-helpers">
									<a href="#" onclick="swapScreen('forgot');return false;">Forgot Password?</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!--/LOGIN -->
			<!-- FORGOT PASSWORD -->
			<section id="forgot" class="<?php echo ($activity == 'forgot' ? 'visible' : '' ); ?>">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div class="login-box-plain">
								<h2 class="bigintro">Employee Reset Password</h2>

                                <?php if($ForgotFormMessages != ''): ?>
                                <div class="alert alert-block alert-danger fade in">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                    <h4><i class="fa fa-times"></i> Oh snap! You got an error!</h4>
                                    <ul>
                                         <?php foreach($ForgotFormMessages as $ForgotFormMessage): ?>
                                        <li><?php echo $ForgotFormMessage; ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                                <?php endif; ?>

								<div class="divide-40"></div>

                                {{ Form::open(array('action' => 'AuthController@showAccess')) }}

                                    <?php echo Form::text('usr'         , null, array('class' => "siteInput Input1")); ?>
                                    <?php echo Form::text('username'    , null, array('class' => "siteInput Input2")); ?>
                                    <?php echo Form::text('email'       , null, array('class' => "siteInput Input3")); ?>
                                    <?php echo Form::text('login_email' , null, array('class' => "siteInput Input4")); ?>


                                    <div class="form-group">
                                            <?php echo Form::label('forgot_email', 'E-Mail Address'); ?>
                                        <i class="fa fa-envelope"></i>
                                            <?php echo Form::text('forgot_email', null, array('class' => "form-control")); ?>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn-lg btn-info">Send Me Reset Instructions</button>
                                    </div>

                                {{ Form::close() }}

								<div class="login-helpers">
									<a href="#" onclick="swapScreen('login');return false;">Back to Employee Login</a> <br>
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