<!doctype html>
<html class="no-js " lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta name="description" content="">
	<title>Nuvoco</title>
	<link rel="icon" href="favicon.ico" type="image/x-icon">

	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/main.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/color_skins.css') ?>">

</head>

<body class="theme-purple authentication sidebar-collapse">

	<div class="page-header">
		<div class="page-header-image" style="background-image:url(assets/images/login.jpg)"></div>
		<div class="container">
			<div class="col-md-12 content-center">
				<div class="card-plain">
					<form class="form" method="post" action="<?php echo base_url('login'); ?>">
					
						<?php get_csrf_security()?>	
						<div class="header">
							<div class="logo-container">
								<img src="<?php echo base_url('assets/images/logo_vmx_white.svg'); ?>" class="img-fluid" alt="">
							</div>
							<h5>Log in</h5>
							
						</div>
						<div class="content">
							<div class="input-group input-lg">

							<?php 
								if(set_value('username')!=""){
									$username = set_value('username');
								}else{
									$username = get_cookie('username');
								}

								if(set_value('password')!=""){
									$password = set_value('password');
								}else{
									$password = get_cookie('password');
								}
								
							?>
								<input type="text" name="username" value="<?php echo $username?>" class="form-control" placeholder="Enter Username">
								<span class="input-group-addon">
									<i class="zmdi zmdi-account-circle"></i>
								</span>
								
							</div>
							<span ><?php echo form_error('username'); ?></span>
							<div class="input-group input-lg">
								<input type="password" name="password" placeholder="Password" class="form-control" value="<?php echo $password?>"/>
								<span class="input-group-addon">
									<i class="zmdi zmdi-lock"></i>
								</span>
								
							</div>
							<span><?php echo form_error('password'); ?></span>
							<div class="input-group input-lg">
								<input type="checkbox" name="remember_me" class="remember_me" value="<?php echo get_cookie('remember_me')?>"  <?php echo (get_cookie('remember_me'))?'checked':''?>/>
								Remember me
								
							</div>
						
						</div>
						<div class="footer text-center">
							<button type="submit" name="submit" class="btn btn-primary btn-round btn-lg btn-block ">SIGN IN</button>
							
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	
	<script>
	
		$('.remember_me').change(function(){
			if(this.checked){
				$(this).attr("value",1);
			}else{
				$(this).attr("value",0);
			}
		})
	</script>


<!-- notify message work -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $.notify.defaults({
        position: 'top right',
        style: 'bootstrap'
      });
      <?php if ($this->session->flashdata('success')) { ?>
        $.notify("<?php echo $this->session->flashdata('success'); ?>", "success");
      <?php }
      if ($this->session->flashdata('error')) { ?>
        $.notify("<?php echo $this->session->flashdata('error'); ?>", "error");
      <?php }
      if ($this->session->flashdata('warning')) { ?>
        $.notify("<?php echo $this->session->flashdata('warning'); ?>", "warn");
      <?php }
      if ($this->session->flashdata('info')) { ?>
        $.notify("<?php echo $this->session->flashdata('info'); ?>", "info");
      <?php } ?>
    });
  </script>
  <!-- notify message work -->
</body>

</html>
