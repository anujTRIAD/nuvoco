
<section class="content margin-custom">
	<div class="block-header">
		<div class="row">
			<div class="col-lg-9 col-md-6 col-sm-12">
				<h2> <?php echo $page_title; ?> </h2>
			</div>
			<div class="col-md-3"> 
				 <a class="btn btn-simple text-white border-white float-right" href="<?php echo base_url('users'); ?>"> <i class="zmdi zmdi-arrow-back"></i> Back </a>  
			</div> 
		</div>
	</div>
	<div class="container-fluid"> 
		<div class="row clearfix">
			<div class="col-lg-12">
				<div class="card">
					<div class="body">
						 <form action="<?php echo base_url('users/add'); ?>" method="post" id="add_user" enctype="multipart/form-data">
                <!-- <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLabel">Add</h4>
                </div> -->
                <div class="modal-body">

					<div class="row">
					<div class="form-group col-sm-3">
						<label class="userlabel">Brand <span class="red">*</span></label>
						<select name="brand" id="brand" class="form-control chosen-select" >
									<option selected="selected" value="" disabled>Select Brand</option>
									<?php if( is_array($brand) && count($brand) > 0 ) { ?>
									<?php foreach ($brand as $brands) { ?>
											<option value="<?php echo $brands['brand']; ?>"><?php echo ucfirst($brands['brand']); ?></option>
									<?php } }?>
						</select>
					</div>
					<div class="form-group col-sm-3">
							<label class="userlabel">Name <span class="red">*</span></label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Name" required >
						</div>

						<div class="form-group col-sm-3">
							<label>Email Id</label>
							<input type="text" class="form-control" id="email" name="email" required placeholder="Email Id">
						</div>	
						<div class="form-group col-sm-3">
							<label>Contact No.</label>
							<input type="text" class="form-control" id="contact_no" name="contact_no" placeholder="Contact No." required>
						</div>
					</div>
                	<div class="row">
						<div class="form-group col-sm-3">
							<label>Role <span class="red">*</span></label>
							<select name="role" class="chosen-select form-control role">
								<option selected="selected" disabled="true" value="">Select</option>
								<?php if( is_array($role) && count($role) > 0 ) { ?>
								<?php foreach ($role as $roles) { ?>
									<?php if($roles['role_name']!='vendor'){?>
										<option value="<?php echo $roles['role_name']; ?>"><?php echo ucfirst($roles['role_name']); ?></option>
								<?php } } }?>
							</select>
						</div>
						<div class="form-group col-sm-3">
							<label>Zone <span class="red">*</span></label>
							<select name="zone" id="zone" class="form-control chosen-select" >
								<option selected="selected" value="" disabled>Select Zone</option>
								<?php if( is_array($zone) && count($zone) > 0 ) { ?>
								<?php foreach ($zone as $zones) { ?>
										<option value="<?php echo $zones['zone']; ?>"><?php echo ucfirst($zones['zone']); ?></option>
								<?php } }?>
							</select>
						</div>
						<div class="form-group col-sm-3">
							<label>State <span class="red">*</span></label>
							<select name="state" id="state" class="form-control chosen-select state">
		
							</select>
						</div>

						<div class="form-group col-sm-3">
							<label>City <span class="red">*</span></label>
							<select name="city" id="city" class="form-control chosen-select city">
							</select>
							</div>
						
					</div>
					<div class="row">
						
						<div class="form-group col-sm-3">
							<label>Username</label>
							<input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
						</div>
						<div class="form-group col-sm-3">
							<label>Password</label>
							<input type="text" class="form-control" id="password" name="password" placeholder="Password" required>
						</div>
					</div>  
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                </div>      
                <div class="modal-footer center">
                    <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-save"></i> Create</button>
                </div>
            </form>
					</div>
				</div>
			</div>
		</div> 
	</div>
</section> 

 
<script type="text/javascript">
	$(document).ready(function(){ 
	$('#zone').chosen();
	$('#zone').on('change', function() {
		zone = $(this).val();
		where = {'region':zone};
		load_selectBox('statename as id,statename as name','citylist',where,'.state','','statename');
		$(function(){
			setTimeout(function(){
				$('#state').chosen();
				$('#state').trigger("chosen:updated");;
			},300)
		})
	});

	$('#state').on('change', function() {
		zone = $('#zone').val();
		state = $(this).val();
		where = {'region':zone,'statename':state};
		load_selectBox('cityname as id,cityname as name','citylist',where,'.city','','cityname');
		$(function(){
			setTimeout(function(){
				$('#city').chosen();
				$('#city').trigger("chosen:updated");;
			},300)
		})
	});

	
	});
</script>
