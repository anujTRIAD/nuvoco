<section class="content margin-custom">
	<div class="block-header">
		<div class="row">
			<div class="col-lg-7 col-md-6 col-sm-12">
				<h2>Projects </h2>
			</div>
			<div class="col-md-5">
				<!-- <button class="btn btn-simple text-white border-white float-right">
					<i class="zmdi zmdi-download"></i> Download
				</button> -->
			</div>
			<div class="col-md-12">
				<nav class="float-right">
					<ol class="breadcrumb ">
						<li class="breadcrumb-item"><a href="<?php echo base_url('project'); ?>">Project</a></li>
						<!-- <li class="breadcrumb-item" style="color: #3b5998;">View</a></li> -->
						<!-- <li class="breadcrumb-item active text-white" aria-current="page">Data</li> -->
					</ol>
				</nav>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row clearfix">
			<div class="col-lg-3 col-md-6 col-sm-12">
				<div class="card">
					<div class="body">
						<div class="row">
							<div class="col-7">
								<h5 class="m-t-0">Project Count</h5> 
							</div>
							<div class="col-5 text-right">
								<h2 class="">2365</h2>
								<!-- <small class="info">of 1Tb</small> -->
							</div>
							<div class="col-12">
								<div class="progress m-t-20" value="20" type="success">
									<div class="progress-bar l-green" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12">
				<div class="card">
					<div class="body">
						<div class="row">
							<div class="col-7">
								<h5 class="m-t-0">Total Budget</h5> 
							</div>
							<div class="col-5 text-right">
								<h2 class="">365</h2>
								<!-- <small class="info">of 1Tb</small> -->
							</div>
							<div class="col-12">
								<div class="progress m-t-20" value="12">
									<div class="progress-bar l-blush" role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100" style="width: 12%;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12">
				<div class="card">
					<div class="body">
						<div class="row">
							<div class="col-7">
								<h5 class="m-t-0">Total Budget Used</h5> 
							</div>
							<div class="col-5 text-right">
								<h2 class="">65</h2>
								<!-- <small class="info">of 100</small> -->
							</div>
							<div class="col-12">
								<div class="progress m-t-20" value="39">
									<div class="progress-bar l-parpl" role="progressbar" aria-valuenow="39" aria-valuemin="0" aria-valuemax="100" style="width: 39%;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12">
				<div class="card">
					<div class="body">
						<div class="row">
							<div class="col-7">
								<h5 class="m-t-0">Percentage</h5> 
							</div>
							<div class="col-5 text-right">
								<h2 class="">2055</h2>
								<!-- <small class="info">of 10</small> -->
							</div>
							<div class="col-12">
								<div class="progress m-t-20" value="89" type="success">
									<div class="progress-bar l-blue" role="progressbar" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100" style="width: 89%;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> 
		<div class="row clearfix">
			<div class="col-lg-12">
				<div class="card">

					<div class="body">
						<div class="table-responsive">
							<button class="btn btn-primary mb-5">
								<i class="zmdi zmdi-download"></i> Download
							</button>
							<!-- <button class="btn btn-primary mb-5">
								<i class="zmdi zmdi-print"></i> Print
							</button>
							<button class="btn btn-primary mb-5">
								<i class="zmdi zmdi-copy"></i> Copy
							</button> -->
							<table class="table table-bordered table-striped table-hover js-basic-example dataTable">
								<thead>
									<tr>
										<!-- <th>Creation Date</th> -->
										<th>Project Name</th>
										<th>Project Type</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th title="No. of vendors">vendors</th>
										<th title="No. of Stores">Stores</th>
										<th>Budget</th>
										<th>Budget used</th>
										<th>Budget used %</th>
										<th>Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<!-- <td>08-Aug-22</td> -->
										<td title="XXXXXX0001XXX">Nuvoco Cement</td>
										<td>Type</td>
										<td><i class="zmdi zmdi-calendar-alt"></i> 08-Aug-22</td>
										<td><i class="zmdi zmdi-calendar-alt"></i> 08-Sep-22</td>
										<td>10</td>
										<td>115</td>
										<td>300000</td>
										<td>200000</td>
										<td class="text-center">
											<div class="progress">
												<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:80%;">
													<!-- <span class="sr-only">80% Complete</span> -->
												</div>
											</div>
											<span class="badge bg-success"><b>80%</b></span>
										</td>
										<td><button class="btn btn-simple text-success border-success font-weight-bold">Completed</button></td>
										<td>
											<a type="button" class="btn btn-primary text-white" href="<?php echo base_url('project/view'); ?>">View</a>
											<a type="button" class="btn btn-warning text-white">Edit</a>
											<a type="button" class="btn btn-danger text-white" href="<?php echo base_url('project/list'); ?>">List</a>
										</td>
									</tr>
									<tr>
										<!-- <td>08-Aug-22</td> -->
										<td title="XXXXXX0002XXX">Double Cement</td>
										<td>Type</td>
										<td><i class="zmdi zmdi-calendar-alt"></i> 08-Aug-22</td>
										<td><i class="zmdi zmdi-calendar-alt"></i> 08-Sep-22</td>
										<td>09</td>
										<td>60</td>
										<td>300000</td>
										<td>200000</td>
										<td class="text-center">
											<div class="progress">
												<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">
													<!-- <span class="sr-only">80% Complete</span> -->
												</div>
											</div>
											<span class="badge bg-success"><b>80%</b></span>
										</td>
										<td><button class="btn btn-simple text-danger border-danger font-weight-bold">Creation<br>In-progress</button></td>
										<td>
											<a type="button" class="btn btn-primary text-white" href="<?php echo base_url('project/view'); ?>">View</a>
											<a type="button" class="btn btn-warning text-white">Edit</a>
											<a type="button" class="btn btn-danger text-white" href="<?php echo base_url('project/list'); ?>">List</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
