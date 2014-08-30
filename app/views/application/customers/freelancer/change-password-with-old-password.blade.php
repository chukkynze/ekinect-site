@extends('layouts.freelancer-cloud')
@section('content')
<div class="container">
	<div class="row">
		<div id="content" class="col-lg-12">

			<!-- PAGE HEADER-->
			<div class="row">
				<div class="col-sm-12">
					<div class="page-header">
						<div class="clearfix">
							<h3 class="content-title pull-left">Change your Password</h3>
						</div>
						<div class="description">Regularly changing your password helps keeps your account secure.</div>
					</div>
				</div>
			</div>
			<!-- /PAGE HEADER -->






			<section id="register" class="visible">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div class="login-box-plain">
								<h2 class="bigintro">Update Member Login</h2>
								<div class="center">
									<h3>Carefully enter your current and new passwords</h3>
								</div>

                                <?php if($FormMessages != ''): ?>
                                <div class="alert alert-block alert-danger fade in">
                                    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
                                    <h4><i class="fa fa-times"></i> Oh snap! You got an error!</h4>
                                    <ul>
                                        <?php foreach($FormMessages as $FormMessage): ?>
                                        <li><?php echo $FormMessage; ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                                <?php endif; ?>


								<div class="divide-40"></div>

                                {{ Form::open(array('method' => 'POST', 'action' => 'FreelancerController@postChangePasswordWithOldPassword')) }}

                                    <div class="form-group">
                                        <?php echo Form::label('current_password', 'Current Password'); ?>
                                        <i class="fa fa-lock"></i>
                                        <?php echo Form::password('current_password', array('class' => "form-control")); ?>
                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('password', 'New Password'); ?>
                                        <i class="fa fa-lock"></i>
                                        <?php echo Form::password('password', array('class' => "form-control")); ?>
                                    </div>
                                    <div class="form-group">
                                        <?php echo Form::label('password_confirmation', 'Re-Type New Password'); ?>
                                        <i class="fa fa-check-square-o"></i>
                                        <?php echo Form::password('password_confirmation', array('class' => "form-control")); ?>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn-lg btn-success">Update Password</button>
                                    </div>

                                {{ Form::close() }}



							</div>
						</div>
					</div>
				</div>
			</section>




			<div class="footer-tools">
				<span class="go-top">
					<i class="fa fa-chevron-up"></i> Top
				</span>
			</div>
		</div><!-- /CONTENT-->
	</div>
</div>
@stop