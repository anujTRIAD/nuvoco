<!doctype html>
<html class="no-js " lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<meta name="description" content="Responsive Bootstrap 4 and web Application ui kit.">
	<title>Nuvoco</title>
	<link rel="icon" href="favicon.ico" type="image/x-icon">

	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/morrisjs/morris.css') ?>" />
	<link rel="stylesheet" href="<?php echo base_url('assets/css/main.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/hm-style.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/color_skins.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css') ?>">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.0/jquery.js"></script>



	<?php
		if( isset($inclusions['css']) ){
			foreach($inclusions['css'] as $file) {
					echo "<link rel='stylesheet' type='text/css' href='".base_url($file.'.css')."' />\n";
			}
		}
		if( isset($inclusions['header_js']) ){
			foreach($inclusions['header_js'] as $header_js) {
				echo "<script type='text/javascript' src='".base_url($header_js.'.js')."'></script>\n";
			}
		}
	?>
	
</head>

<body class="theme-purple index2 d-flex flex-column min-vh-100" data-url="<?php echo base_url()?>" data-token="<?php echo $this->security->get_csrf_hash()?>">


	<nav class="navbar p-l-5 p-r-5">
		<ul class="nav navbar-nav navbar-left">
			<li>
				<div class="navbar-header">
					<a class="navbar-brand" href="<?php base_url('dashboard'); ?>"><img src="<?php echo base_url('assets/images/logo_vmx_white.svg')?>" class="img-fluid" width="100%" alt="VMX"></a>
				</div>
			</li>
		</ul>

		<ul class="nav navbar-nav navbar-left float-right">
			<li class="hidden-sm-down">
				<div class="input-group">
					<input type="text" class="form-control" placeholder="Search...">
					<span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
				</div>
			</li>
			<li class="dropdown"> <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="zmdi zmdi-notifications"></i>
					<div class="notify"><span class="heartbit"></span><span class="point"></span></div>
				</a>
				<ul class="dropdown-menu pullDown">
					<li class="body">
						<ul class="menu list-unstyled">
							<li>
								<a href="">
									<div class="media">
										<img class="media-object" src="assets/images/xs/avatar2.jpg" alt="">
										<div class="media-body">
											<span class="name">Sophia <span class="time">30min ago</span></span>
											<span class="message">There are many variations of passages</span>
										</div>
									</div>
								</a>
							</li>
							<li>
								<a href="">
									<div class="media">
										<img class="media-object" src="assets/images/xs/avatar3.jpg" alt="">
										<div class="media-body">
											<span class="name">Sophia <span class="time">31min ago</span></span>
											<span class="message">There are many variations of passages of Lorem
												Ipsum</span>
										</div>
									</div>
								</a>
							</li>
							<li>
								<a href="">
									<div class="media">
										<img class="media-object" src="assets/images/xs/avatar4.jpg" alt="">
										<div class="media-body">
											<span class="name">Isabella <span class="time">35min ago</span></span>
											<span class="message">There are many variations of passages</span>
										</div>
									</div>
								</a>
							</li>
							<li>
								<a href="">
									<div class="media">
										<img class="media-object" src="assets/images/xs/avatar5.jpg" alt="">
										<div class="media-body">
											<span class="name">Alexander <span class="time">35min ago</span></span>
											<span class="message">Contrary to popular belief, Lorem Ipsum random</span>
										</div>
									</div>
								</a>
							</li>
							<li>
								<a href="">
									<div class="media">
										<img class="media-object" src="assets/images/xs/avatar6.jpg" alt="">
										<div class="media-body">
											<span class="name">Grayson <span class="time">1hr ago</span></span>
											<span class="message">There are many variations of passages</span>
										</div>
									</div>
								</a>
							</li>
						</ul>
					</li>
					<li class="footer"> <a href="">View All</a> </li>
				</ul>
			</li>
			<li class="dropdown"> <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button"><i class="zmdi zmdi-flag"></i>
					<div class="notify">
						<span class="heartbit"></span>
						<span class="point"></span>
					</div>
				</a>
				<ul class="dropdown-menu pullDown">
					<li class="header">Project</li>
					<li class="body">
						<ul class="menu tasks list-unstyled">
							<li>
								<a href="">
									<div class="progress-container progress-primary">
										<span class="progress-badge">eCommerce Website</span>
										<div class="progress">
											<div class="progress-bar" role="progressbar" aria-valuenow="86" aria-valuemin="0" aria-valuemax="100" style="width: 86%;">
												<span class="progress-value">86%</span>
											</div>
										</div>
										<ul class="list-unstyled team-info">
											<li class="m-r-15"><small class="text-muted">Team</small></li>
											<li><img src="assets/images/xs/avatar2.jpg" alt="Avatar"></li>
											<li><img src="assets/images/xs/avatar3.jpg" alt="Avatar"></li>
											<li><img src="assets/images/xs/avatar4.jpg" alt="Avatar"></li>
										</ul>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:void(0);">
									<div class="progress-container progress-info">
										<span class="progress-badge">iOS Game Dev</span>
										<div class="progress">
											<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;">
												<span class="progress-value">45%</span>
											</div>
										</div>
										<ul class="list-unstyled team-info">
											<li class="m-r-15"><small class="text-muted">Team</small></li>
											<li><img src="assets/images/xs/avatar10.jpg" alt="Avatar"></li>
											<li><img src="assets/images/xs/avatar9.jpg" alt="Avatar"></li>
											<li><img src="assets/images/xs/avatar8.jpg" alt="Avatar"></li>
											<li><img src="assets/images/xs/avatar7.jpg" alt="Avatar"></li>
											<li><img src="assets/images/xs/avatar6.jpg" alt="Avatar"></li>
										</ul>
									</div>
								</a>
							</li>
							<li>
								<a href="javascript:void(0);">
									<div class="progress-container progress-warning">
										<span class="progress-badge">Home Development</span>
										<div class="progress">
											<div class="progress-bar" role="progressbar" aria-valuenow="29" aria-valuemin="0" aria-valuemax="100" style="width: 29%;">
												<span class="progress-value">29%</span>
											</div>
										</div>
										<ul class="list-unstyled team-info">
											<li class="m-r-15"><small class="text-muted">Team</small></li>
											<li><img src="assets/images/xs/avatar5.jpg" alt="Avatar"></li>
											<li><img src="assets/images/xs/avatar2.jpg" alt="Avatar"></li>
											<li><img src="assets/images/xs/avatar7.jpg" alt="Avatar"></li>
										</ul>
									</div>
								</a>
							</li>
						</ul>
					</li>
					<li class="footer"><a href="">View All</a></li>
				</ul>
			</li>
			<li class="">
				<a href="<?php echo base_url('dashboard/logout'); ?>" class="mega-menu" data-close="true"><i class="zmdi zmdi-power"></i></a>
				<a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="zmdi zmdi-settings zmdi-hc-spin"></i></a>
			</li>
		</ul>
	</nav>
	
	<div class="menu-container">
		<div class="menu">
			<ul>
				<li><a href="<?php echo base_url('dashboard') ?>"><i class="zmdi zmdi-view-dashboard"></i> Dashboard</a></li>
				<li><a href="<?php echo base_url('project') ?>"> <i class="zmdi zmdi-settings-square"></i> project</a></li>
				<li class="menu-dropdown-icon"><a href="javascript:void(0);"><i class="zmdi zmdi-assignment"></i> Masters</a>
					<ul class="pulldown">
						<li><a href="<?php echo base_url('store') ?>">Store master</a></li>
						<li><a href="<?php echo base_url('vendor') ?>">Vendor Master</a></li>
						<li><a href="<?php echo base_url('element') ?>">Element Master</a></li>
						<li><a href="<?php echo base_url('element-rate') ?>">Element Rate Master</a></li>
						<li><a href="<?php echo base_url('users') ?>">User Master</a></li>
					</ul>
				</li>
				<li><a href="<?php echo base_url('reports') ?>"><i class="zmdi zmdi-assignment"></i> Reports</a></li>
			</ul>
		</div>
	</div>
