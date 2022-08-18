<div class="content-wrapper">
	<section class="content-header">
		<a href="<?php echo base_url('rate_master'); ?>" class="pull-right btn btn-primary"> All Elements</a>
		<h1><?php echo $page_title; ?></h1>
	</section>
	<section class="content">
		<?php echo get_flashdata('message'); ?>
		<div class="box">
			<div class="box-body">
				<form action="<?php echo base_url('element/add'); ?>" method="post" id="add_element" enctype="multipart/form-data">
					<div class="row">
						<div class="form-group col-sm-4">
							<label>Element Name <span class="red">*</span></label>
							<input type="text" class="form-control" id="element_name" name="element_name" placeholder="Element Name" required>
							<p class="errorp" style="color: red"></p>
						</div>
						<div class="form-group col-sm-4">
							<label>Element Type <span class="red">*</span></label>
							<select name="element_type" id="element_type" class="chosen-select form-control" required >
								<option value="">Select</option>
								<option value="In Shop Branding">In shop branding(ISB)</option>
								<option value="Signage">Signage</option>
							</select>
						</div>
						
						<div class="form-group col-sm-4">
							<label>Element Details</label>
							<textarea class="form-control" id="element_description" name="element_description" placeholder="Element Details"></textarea>
							<p class="errorp" style="color: red"></p>
						</div>					
					</div>	
					
				    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
					<center>
						<button type="submit" name="submit" class="btn btn-success"><i class="fa fa-save"></i> Submit</button>
					</center>	
				</form>
			</div>
		</div>
	</section>
</div>
<script>	
$("#add_element").validate({			
        rules: {
				    element_name: {
                        required: true
                    },

                    element_type: {
                        required: true
                    },
                    
                    /*element_rate: {
                        required: true
                    },*/
                },			
        messages: {				
            element_name: "Please enter Element Name",				
            element_type: "Please Select Element Type"			
            }	
    });	
</script>