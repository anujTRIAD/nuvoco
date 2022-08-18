 <style>
	.raphael-group-90-creditgroup>text>tspan {
		display: none !important;
	}
</style>
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
							<label>Role <span class="red">*</span></label>
							<select name="role" class="chosen-select form-control role" required="required">
								<option selected="selected" disabled="true" value="">Select</option>
								<?php if( is_array($role) && count($role) > 0 ) { ?>
								<?php foreach ($role as $roles) { ?>
								<option value="<?php if($roles['role_name'] != 'vendor'){ echo $roles['role_name']; }?>" <?php echo ($roles['role_name'] == $user['role'])?'selected':'';?> ><?php if($roles['role_name'] != 'vendor'){ echo ucfirst($roles['role_name']); }?></option>
								<?php } } ?>
							</select>
						</div>
						<div class="form-group col-sm-3">
							<label>Zone <span class="red">*</span></label>
							<select name="zone[]" id="zone" class="form-control chosen-select" multiple required="">
								<option value="" disabled>Select Zone</option>
								<option value="East" <?php echo ('East' == $user['zone'])?'selected':'';?>>East</option>
								<option value="West" <?php echo ('West' == $user['zone'])?'selected':'';?> >West</option>
								<option value="North" <?php echo ('North' == $user['zone'])?'selected':'';?> >North</option>
								<option value="South" <?php echo ('South' == $user['zone'])?'selected':'';?> >South</option>
							</select>
						</div>
						<div class="form-group col-sm-3">
							<label>State <span class="red">*</span></label>
							<select name="state[]" id="state" class="form-control chosen-select" multiple required>
							</select>
							<span class="error" id="state_error"></span>
						</div>
						<div class="form-group col-sm-3">
							<label class="userlabel">Name <span class="red">*</span></label>
							<input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name'] ?>" required >
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-3">
							<label>Email Id</label>
							<input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email_id'] ?>" required >
						</div>	
						<div class="form-group col-sm-3">
							<label>Contact No.</label>
							<input type="text" class="form-control" id="contact_no" name="contact_no" value="<?php echo $user['contact_person'] ?>" required>
						</div>
						<div class="form-group col-sm-3">
							<label>Username</label>
							<input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username'] ?>" required>
						</div>
						<div class="form-group col-sm-3">
							<label>Password</label>
							<input type="password" class="form-control" id="password" name="password" value="<?php echo $user['password'] ?>" required>
						</div>
					</div>  
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                </div>      
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-save"></i> Update</button>
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
	$(".chosen-select").chosen();  

	var add_user = $('#add_user'); 
	add_user.validate({
		rules: {
			name: {
				required : true, 
			},
			contact_no: {
				required : true, 
			},
			username: {
				required : true, 
			},
			role: {
				required : true, 
			},
			zone : {
				required : true, 
			},
			state: {
				required: true,
			},
			role: {
				required: true,
			},
			 
			password: {
				required: true,
			},
			 
			email: {
				required: true,
				email : true,
				// remote: {
				// 	url: "<?php //echo base_url('user/check_email') ?>",
				// 	type: "post",
				// 	data: {
				// 		email: function() {
				// 			return $( "#email" ).val();
				// 		},
				// 		"<?php //echo $this->security->get_csrf_token_name(); ?>": "<?php //echo $this->security->get_csrf_hash(); ?>"
				// 	},
				// 	async:false
				// }
			},
			contact_no: {
				required: true,
				number : true,
				maxlength : 10, 
				minlength : 10, 
				// remote: {
				// 	url: "<?php //echo base_url('user/check_contact_no') ?>",
				// 	type: "post",
				// 	data: {
				// 		mobile: function() {
				// 			return $( "#contact_no" ).val();
				// 		},
				// 		"<?php //echo $this->security->get_csrf_token_name(); ?>": "<?php//echo $this->security->get_csrf_hash(); ?>"
				// 	},
				// 	async:false
				// }
			},
			 
			 
		},
		messages: { 
			email: {
				remote: "Email is already in use"
			}, 
			mobile: {
				remote: "Mobile is already in use"
			}, 
			file: {
				extension: "Only jpeg,png allowed"
			}, 
		},
		// errorPlacement: function(error, element) {}
	});


	$('#zone').on('change', function() {
		var zone = $(this).val();
		var post_data = {
		'zone': zone,
		'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		if(zone) {
			$.ajax({
				url:'<?php echo base_url('filter/loadState') ?>',
				type: "POST",
				data:post_data,
				dataType: "json",
				success:function(data) 
				{
					$('#state').empty();
					//$('#state').append("<option value='all'>Select All</option>");
					$.each(data, function(key, value) {
						$('#state').append('<option value="'+ value.statename +'">'+ value.statename +'</option>');
					});
					$('.chosen-select').trigger('chosen:updated');
				}
			});
		}else{
			$('#state').empty();
		}
	});
	
	/* Select State */
	$('#state').on('change', function() {
		var state = $(this).val();
		var post_data = {
		'state': state,
		'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
		};
		if(state) {
			$.ajax({
				url:'<?php echo base_url('filter/loadCity') ?>',
				type: "POST",
				data:post_data,
				dataType: "json",
				success:function(data) 
				{
					$('#city').empty();
					//$('#city').append("<option value='all'>Select All</option>");
					$.each(data, function(key, value) {
						$('#city').append('<option value="'+ value.cityname +'">'+ value.cityname +'</option>');
					});
					$('.chosen-select').trigger('chosen:updated');
				}
			});
		}else{
			$('#city').empty();
		}
	});


	});
</script>
