<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login / Sign Up</title>
  <link rel="stylesheet" href="<?php echo e(asset('css/styles.css')); ?>"/>
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>
<body>

	<div class="section">
		<div class="container">
			<div class="row full-height justify-content-center">
				<div class="col-12 text-center align-self-center py-5">
					<div class="section pb-5 pt-5 pt-sm-2 text-center">
						<h6 class="mb-0 pb-3"><span>Log In </span><span>Sign Up</span></h6>
			          	<input class="checkbox" type="checkbox" id="reg-log" name="reg-log"/>
			          	<label for="reg-log"></label>
						<div class="card-3d-wrap mx-auto">
							<div class="card-3d-wrapper">
								<!-- ✅ LOGIN FORM - Diubah menggunakan form dengan action ke route baru -->
								<div class="card-front">
									<div class="center-wrap">
										<div class="section text-center">
											<h4 class="mb-4 pb-3">Log In</h4>
											<form action="<?php echo e(route('login.direct')); ?>" method="POST">
												<?php echo csrf_field(); ?>
												<div class="form-group">
													<input type="email" name="logemail" class="form-style" placeholder="Your Email" id="logemail" autocomplete="off">
													<i class="input-icon uil uil-at"></i>
												</div>	
												<div class="form-group mt-2">
													<input type="password" name="logpass" class="form-style" placeholder="Your Password" id="logpass" autocomplete="off">
													<i class="input-icon uil uil-lock-alt"></i>
												</div>
												<button type="submit" class="btn mt-4">Submit</button>
											</form>
                            				<p class="mb-0 mt-4 text-center"><a href="#0" class="link">Forgot your password?</a></p>
				      					</div>
			      					</div>
			      				</div>
								
								<!-- ✅ SIGN UP FORM - Diubah menggunakan form dengan action ke route baru -->
								<div class="card-back">
									<div class="center-wrap">
										<div class="section text-center">
											<h4 class="mb-4 pb-3">Sign Up</h4>
											<form action="<?php echo e(route('signup.direct')); ?>" method="POST">
												<?php echo csrf_field(); ?>
												<div class="form-group">
													<input type="text" name="logname" class="form-style" placeholder="Your Full Name" id="logname" autocomplete="off">
													<i class="input-icon uil uil-user"></i>
												</div>	
												<div class="form-group mt-2">
													<input type="email" name="logemail" class="form-style" placeholder="Your Email" id="logemail" autocomplete="off">
													<i class="input-icon uil uil-at"></i>
												</div>	
												<div class="form-group mt-2">
													<input type="password" name="logpass" class="form-style" placeholder="Your Password" id="logpass" autocomplete="off">
													<i class="input-icon uil uil-lock-alt"></i>
												</div>
												<button type="submit" class="btn mt-4">Submit</button>
											</form>
				      					</div>
			      					</div>
			      				</div>
			      			</div>
			      		</div>
			      	</div>
		      	</div>
	      	</div>
	    </div>
	</div>

</body>
</html>
<?php /**PATH C:\laragon\www\si_bioskop\resources\views/auth/login.blade.php ENDPATH**/ ?>