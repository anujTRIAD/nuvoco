<section class="content margin-custom">
	<div class="block-header">
		<div class="row">
			<div class="col-lg-7 col-md-6 col-sm-12">
				<h2>Projects Name
				</h2>
			</div>
			<div class="col-md-5">
				<a class="btn btn-simple text-white border-white float-right" href="<?php echo base_url('project'); ?>">
					<i class="zmdi zmdi-arrow-back"></i> Back
				</a>
			</div>
		</div>
	</div>
	<div class="container-fluid">

		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 mx-auto">
				<div class="card">
					<div class="body">
						<div class="demo-masked-input">
							<form action="" method="post" id="form_validation">
								<div class="row clearfix p-5">
									<div class="col-lg-6 col-md-6 mb-3"> <b>Creation Date</b>
										<div class="input-group">
											<span class="input-group-addon"><i class="zmdi zmdi-calendar"></i> </span>
											<input type="text" class="form-control date" placeholder="Ex: 30/07/2016">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>Project id</b>
										<div class="input-group">
											<span class="input-group-addon"><i class="zmdi zmdi-key"></i></span>
											<input type="text" class="form-control" placeholder="Name" name="name" required="" aria-required="true" aria-invalid="true">
											<label id="name-error" class="error" for="name">This field is required.</label>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>Type of Project</b>
										<div class="input-group">
											<span class="input-group-addon"><i class="zmdi zmdi-key"></i></span>
											<input type="text" class="form-control" placeholder="Name" name="name" required="" aria-required="true" aria-invalid="true">
											<label id="name-error" class="error" for="name">This field is required.</label>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>Project Name</b>
										<div class="input-group">
											<span class="input-group-addon"><i class="zmdi zmdi-key"></i></span>
											<input type="text" class="form-control key" placeholder="Ex: XXX0-XXXX-XX00-0XXX">
											<label id="name-error" class="error" for="name">This field is required.</label>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>Start Date</b>
										<div class="input-group">
											<span class="input-group-addon"><i class="zmdi zmdi-key"></i></span>
											<input type="text" class="form-control" name="date" required="" aria-required="true" aria-invalid="false" placeholder="YYYY-MM-DD format">
											<label id="date-error" class="error" for="date" style="display: none;"></label>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>End Date</b>
										<div class="input-group">
											<span class="input-group-addon"><i class="zmdi zmdi-key"></i></span>
											<input type="text" class="form-control" name="date" required="" aria-required="true" aria-invalid="false" placeholder="YYYY-MM-DD format">
											<label id="date-error" class="error" for="date" style="display: none;"></label>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>No. of vendor</b>
										<div class="input-group">
											<span class="input-group-addon"><i class="zmdi zmdi-key"></i></span>
											<input type="number" class="form-control" name="number" required="" aria-required="true" aria-invalid="true">
											<label id="number-error" class="error" for="number">This field is required.</label>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>No. of Stores</b>
										<div class="input-group">
											<span class="input-group-addon"><i class="zmdi zmdi-key"></i></span>
											<input type="number" class="form-control" name="number" required="" aria-required="true" aria-invalid="true">
											<label id="number-error" class="error" for="number">This field is required.</label>
										</div>
									</div>
									<button class="btn btn-primary mx-auto">Normal</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</section>
