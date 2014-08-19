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
			<!-- LOGIN -->
			<section id="verification-details" class="visible">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div class="login-box-plain">
								<h2 class="bigintro">Verification Details</h2>
								<div class="center">
									<strong>Your Email Address is Verified.</strong><br>
									<strong>Please, personalize your account to access your Member Section.</strong>
								</div>

                                <?php if($VerificationDetailsFormMessages != ''): ?>
                                <div class="alert alert-block alert-danger fade in">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                    <h4><i class="fa fa-times"></i> Oh snap! You got an error!</h4>
                                    <ul>
                                        <?php foreach($VerificationDetailsFormMessages as $VerificationDetailsFormMessage): ?>
                                        <li><?php echo $VerificationDetailsFormMessage; ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                                <?php endif; ?>


								<div class="divide-40"></div>

                                {{ Form::open(array('method' => 'POST', 'action' => 'AuthController@processVerificationDetails')) }}

                                    <?php echo Form::text('usr'         , null, array('class' => "siteInput Input1")); ?>
                                    <?php echo Form::text('username'    , null, array('class' => "siteInput Input2")); ?>
                                    <?php echo Form::text('email'       , null, array('class' => "siteInput Input3")); ?>
                                    <?php echo Form::text('login_email' , null, array('class' => "siteInput Input4")); ?>

                                    <?php echo Form::hidden('vcode', $vcode); ?>

                                    <div class="form-group">
                                        <?php echo Form::label('first_name', 'First Name'); ?>
                                        <?php echo Form::text('first_name', $firstName, array('class' => "form-control")); ?>
                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('last_name', 'Last Name'); ?>
                                        <?php echo Form::text('last_name', $lastName, array('class' => "form-control")); ?>
                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('gender', 'Gender'); ?>
                                        <?php echo Form::select('gender',   array
                                                                            (
                                                                                '0' => 'Please choose...',
                                                                                '1' => 'Female',
                                                                                '2' => 'Male'
                                                                            ),  $gender, array('class' => "form-control"));
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('member_type', 'Which are you?'); ?>
                                        <?php echo Form::select('member_type',  array
                                                                                (
                                                                                    '0' => 'Please choose...',
                                                                                    '1' => 'Vendor',
                                                                                    '2' => 'Freelancer',
                                                                                    '3' => 'Vendor Client',
                                                                                ),  $memberType, array('class' => "form-control"));
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('zipcode', 'ZipCode'); ?>
                                        <?php echo Form::text('zipcode', $zipCode, array('class' => "form-control")); ?>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn-lg btn-primary">Personalize</button>
                                    </div>

                                {{ Form::close() }}

							</div>
						</div>
					</div>
				</div>
			</section>
			<!--/LOGIN -->
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
			App.setPage("verification-details");  //Set current page
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