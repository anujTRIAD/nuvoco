<style>
	.btn-round {
		border-radius: 5px !important;
	}
</style>
<section class="content margin-custom">
	<div class="block-header">
		<div class="row">
			<div class="col-lg-7 col-md-6 col-sm-12">
				<h2>Add project
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
							<form action="" method="post" id="">
								<div class="row clearfix p-5">
									<div class="col-lg-6 col-md-6 mb-3"> <b>State</b>
										<select class="form-control show-tick">
											<option value="">-- Please select State--</option>
											<option value="West Bengal">West bengal</option>
											<option value="Bihar">Bihar</option>
											<option value="Utter Pradesh">Utter Pradesh</option>
										</select>
										<label id="name-error" class="error" for="name">This field is required.</label>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>Project Type</b>
										<select class="form-control show-tick">
											<option value="">-- Please Project Type--</option>
											<option value="Branding">Branding</option>
											<option value="Store Painting">Store Painting</option>
											<option value="Wall Painting">Wall Painting</option>
										</select>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>Project Name</b>
										<div class="input-group">
											<input type="text" class="form-control key" placeholder="Project Name">
											<label id="name-error" class="error" for="name">This field is required.</label>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>Budget Amount</b>
										<div class="input-group">
											<input type="number" class="form-control key" placeholder="Budget Amount">
											<label id="name-error" class="error" for="name">This field is required.</label>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>Start Date</b>
										<div class="input-group">
											<span class="input-group-addon">
												<i class="zmdi zmdi-calendar"></i>
											</span>
											<input type="text" class="form-control datetimepicker" placeholder="Please choose date...">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>End Date</b>
										<div class="input-group">
											<span class="input-group-addon">
												<i class="zmdi zmdi-calendar"></i>
											</span>
											<input type="text" class="form-control datetimepicker" placeholder="Please choose date...">
										</div>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>Check on PO Exceeding</b>
										<select class="form-control show-tick">
											<option value="">-- Please Select --</option>
											<option value="yes">Yes</option>
											<option value="no">No</option>
										</select>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>Project Description</b>
										<div class="form-group">
											<div class="form-line">
												<textarea rows="1" class="form-control no-resize" placeholder="Please type what you want..."></textarea>
											</div>
										</div>
									</div>
								</div>



								<div class="row clearfix p-5">
									<div class="col-md-12">
										<h5>Choose Elements for this Project</h5>
									</div>
									<div class="col-lg-6 col-md-6 mb-3"> <b>Element Category</b>
										<select class="form-control show-tick">
											<option value="">-- Please Select Element Category--</option>
											<option value="GSB">GSB</option>
											<option value="ACP">ACP</option>
											<option value="In-shop">In-shop</option>
											<option value="Wall Painting">Wall Painting</option>
											<option value="Store Painting">Store Painting</option>
										</select>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-0 mb-3"></div>
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
