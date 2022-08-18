<style>
	.raphael-group-90-creditgroup>text>tspan {
		display: none !important;
	}
</style>
<section class="content margin-custom">
	<div class="block-header">
		<div class="row">
			<div class="col-lg-9 col-md-6 col-sm-12">
				<h2>Projects Name
				</h2>
			</div>
			<div class="col-md-3">
				<a class="btn btn-simple text-white border-white float-right" href="<?php echo base_url('project'); ?>">
					<i class="zmdi zmdi-arrow-back"></i> Back
				</a>
			</div>
			<div class="col-md-12">
				<nav class="float-right">
					<ol class="breadcrumb ">
						<li class="breadcrumb-item"><a href="<?php echo base_url('project'); ?>">Project</a></li>
						<li class="breadcrumb-item" style="color: #3b5998;">View</a></li>
						<!-- <li class="breadcrumb-item active text-white" aria-current="page">Data</li> -->
					</ol>
				</nav>
			</div>
		</div>
	</div>
	<div class="container-fluid">

		<div class="row clearfix">
			<div class="col-lg-12">
				<div class="card">
					<div class="body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th>Creation Date</th>
										<th>Project Id</th>
										<th>Project Type</th>
										<th>Project Name</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th title="No. of vendors">vendors</th>
										<th title="No. of Stores">Stores</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><i class="zmdi zmdi-calendar-alt"></i> 08-Aug-22</td>
										<td title="Nuvoco Cement">XXXXXX0001XXX</td>
										<td>Type</td>
										<td>Nuvoco Cement</td>
										<td><i class="zmdi zmdi-calendar-alt"></i> 08-Aug-22</td>
										<td><i class="zmdi zmdi-calendar-alt"></i> 08-Sep-22</td>
										<td>10</td>
										<td>115</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row clearfix">
		<div class="col-md-6 col-lg-6">
				<div class="card">
					<div class="header">
						<h2><strong>Budget</strong> vs Expense</h2>
					</div>
					<div class="body">
						<div id="m_donut_chart">
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6">
				<div class="card">
					<div class="header">
						<h2><strong>Project</strong> Status</h2>

					</div>
					<div class="body">
						<div id="graph2"></div>
					</div>
				</div>
			</div>
		</div>

	</div>
</section>
