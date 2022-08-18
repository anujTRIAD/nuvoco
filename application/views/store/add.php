<?php if($this->session->userdata('brand') == 'Exide'){  $status = "EC Status"; } else if($this->session->userdata('brand') == 'SF'){  $status = "PBO Status"; } else {  $status = "EC Status"; } ?>

<div class="content-wrapper">
    <section class="content-header">
        <a href="<?php echo base_url('store'); ?>" class="pull-right btn btn-info"><i class="fa fa-arrow"></i>Back</a>
        <h1><?php echo $page_title; ?></h1>
    </section>
    
    <section class="content">
    	<?php echo get_flashdata('message'); ?>
        <div>
            <div class="modal-content">
                <form action="<?php echo base_url('store/add_store'); ?>" method="post" id="add_user" enctype="multipart/form-data">
                <div class="modal-body">
                	<div class="row">
                	    <div class="form-group col-sm-4">
							<label>Type of Channel Partner <span class="red">*</span></label>
							<select name="channel_partner_type" class="select form-control channel_partner_type" required="required">
							    <option value="" selected>Select Channel Partner</option>
							    <?php if( $this->session->userdata('brand') == 'Exide') { ?>
    								<option value="Dealer">Dealer</option>
    								<option value="Sub Dealer">Sub Dealer</option>
								<?php } else if($this->session->userdata('brand') == 'SF'){?>
								    <option value="Dealer">Dealer</option>
    								<option value="Registered Retailer">Registered Retailer</option>
    							<?php } ?>	
							</select>            
						</div>
						
						<div class="form-group col-sm-4">
							<label class="userlabel">SAP Code <span class="red">*</span></label>
							<input type="text" class="form-control" id="sap_code" name="sap_code" placeholder="SAP Code" required>
						</div>
						
						
						<div class="form-group col-sm-4">
							<label class="userlabel">Store Name <span class="red">*</span></label>
							<input type="text" class="form-control" id="store_name" name="store_name" placeholder="Store Name" required>
						</div>
						<!--<div class="form-group col-sm-3">
							<label class="userlabel">Channel Partner Name <span class="red">*</span></label>
							<input type="text" class="form-control" id="channel_partner_name" name="channel_partner_name" placeholder="Name" required>
						</div>-->
					</div>
					
					<div class="row">
					    
					    <div class="form-group col-sm-4">
							<label>Dealer Code </label>
							<input type="text" class="form-control" id="dealer_code" name="dealer_code" placeholder="Dealer Code">
						</div>
					    
					    <div class="form-group col-sm-4">
							<label>Dealer Name </label>
							<input type="text" class="form-control" id="dealer_name" name="dealer_name" placeholder="Dealer Name">
						</div>
					    
					    <div class="form-group col-sm-4">
							<label>TMM Zone <span class="red">*</span></label>
							<select name="tmm_zone" class="chosen-select form-control tmm_zone" required="required">
								<option value="">Select Zone</option>
								
								<?php if( is_array($zones) && count($zones) > 0 ) { ?>
								<?php foreach ($zones as $zone) { ?>
								<option value="<?php echo $zone['zone'];?>"><?php echo ucfirst($zone['zone']);?></option>
								<?php } } ?>
							
							</select>            
						</div>
					</div>
					
					<div class="row">	
						<div class="form-group col-sm-4">
							<label>Region <span class="red">*</span></label>
							<select name="region" class="chosen-select form-control region" required="required">
								<option></option>
							<!--	<?php if( is_array($regions) && count($regions) > 0 ) { ?>
								<?php foreach ($regions as $region) { ?>
								<option value="<?php echo $region['region'];?>"><?php echo ucfirst($region['region']);?></option>
								<?php } } ?>-->
							</select>            
						</div>
						<!-- <div class="form-group col-sm-4">
							<label> Hub </label> <span class="red">*</span></label>
							<select name="hub" class="chosen-select form-control hub" required="required">
								<option></option>
								<?php if( is_array($hubs) && count($hubs) > 0 ) { ?>
								<?php foreach ($hubs as $hub) { ?>
								<option value="<?php echo $hub['name'];?>"><?php echo ucfirst($hub['name']);?></option>
								<?php } } ?>
							</select>            
						</div>
						<div class="form-group col-sm-4">
							<label>Spoke <span class="red">*</span></label>
							<select name="spoke" class="chosen-select form-control spoke" required="required">
								<option></option>
								<?php if( is_array($spokes) && count($spokes) > 0 ) { ?>
								<?php foreach ($spokes as $spoke) { ?>
								<option value="<?php echo $spoke['name'];?>"><?php echo ucfirst($spoke['name']);?></option>
								<?php } } ?>
							</select>            
						</div> -->
					</div>
					
					<div class="row">	
						<div class="form-group col-sm-3">
							<label>Address Line 1<span class="red">*</span></label>
							<textarea name="address" id="address" cols="30" rows="2"  class="form-control" required="required"></textarea>
						</div>	
						<div class="form-group col-sm-3">
							<label>Address Line 2 </label>
							<textarea name="address2" id="address2" cols="30" rows="2"  class="form-control" ></textarea>
						</div>
						<div class="form-group col-sm-3">
							<label>Pincode <span class="red">*</span></label>
							<input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pincode" required>
						</div>
						<div class="form-group col-sm-3">
							<label>Contact No / Mobile <span class="red">*</span></label>
							<input type="text" class="form-control" id="mobile" name="mobile" placeholder="Contact No / Mobile" required minlength="10" maxlength="10" required="">
						</div>
					</div>
					
					<div class="row">
					    
					    <div class="form-group col-sm-4">
							<label><?php echo $status;?> <span class="red">*</span></label>
							
							<select name="ec_status" class="chosen-select form-control ec_status" required="required">
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>  
						</div>
					    
					    <div class="form-group col-sm-4">
							<label>Category <span class="red">*</span></label>
							<select name="category" id="category" class="chosen-select form-control" required >
								<option value="">Select</option>
							</select>
						</div>
					</div>
					
					<?php if($this->session->userdata('brand') == 'Exide'){  ?>
					
    					<div class="row">
    						<div class="form-group col-sm-6">
    							<label>Signage Type (1st Installation) <span class="red">*</span></label>
    						    <select name="signage_type_1st_intallation" class="chosen-select form-control signage_type_1st_intallation" required="required">
    							</select>
    						</div>
    						
    						<div class="input-group date col-sm-6">
    					        <label>Installation Date </label>
    							<input type="text" name="1st_installation_date" id="1st_installation_date" readonly class="form-control" />
    							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
    						</div>
    						
    					</div>
    					
    					<div class="row">
    						<div class="form-group col-sm-6">
    							<label>Signage Type (2nd Installation) </label>
    							<select name="signage_type_2st_intallation" class="chosen-select form-control signage_type_2st_intallation" >
    							</select>
    						</div>
    						
    						<div class="input-group date col-sm-6">
    					        <label>Installation Date </label>
    							<input type="text" name="2nd_installation_date" id="2nd_installation_date" readonly class="form-control" />
    							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
    						</div>
    					</div>
    					
    					<div class="row">
    					    <div class="form-group col-sm-6">
    							<label>In-shop Branding Status (1st Installation) <span class="red">*</span></label>
    						    <select name="in_shop_branding_status_1st_installation" class="chosen-select form-control in_shop_branding_status_1st_installation" required="required">
    								<option value="">Select</option>
    								<option value="Yes">Yes</option>
    								<option value="No">No</option>
    							</select>
    						
    						</div>
    					    
    					    <div class="input-group date col-sm-6">
    					        <label>In-shop Branding Date </label>
    							<input type="text" name="in_shop_branding_1st_date" id="in_shop_branding_1st_date" readonly class="form-control" />
    							
    						</div>
    					</div>
    					
    					<div class="row">	
    						<div class="form-group col-sm-6">
    							<label>In-shop Branding Status (2nd Installation) </label>
    						    <select name="in_shop_branding_status_2st_installation" class="chosen-select form-control in_shop_branding_status_2st_installation" >
    								<option value="">Select</option>
    								<option value="Yes">Yes</option>
    								<option value="No">No</option>
    							</select>
    						</div>
    					    
    					    <div class="input-group date col-sm-6">
    					        <label>In-shop Branding Date </label>
    							<input type="text" name="in_shop_branding_2nd_date" id="in_shop_branding_2nd_date" readonly class="form-control"/>
    								
    						</div>
    					</div>
				    <?php } ?>
					
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                </div>      
                <div class="modal-footer center">
                    <button type="submit" class="btn btn-success" name="submit"><i class="fa fa-save"></i> Create</button>
                </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
	$(document).ready(function(){
	    
    	$('select[name="channel_partner_type"]').on("change", function(e){
    		
    		var partner_name = $(this).val();
    		
            <?php if( $this->session->userdata('brand') == 'Exide') { ?>
        		if(partner_name =='Dealer'){
        		    console.log("dealer");
        		    $('select[name="signage_type_1st_intallation"]').html('<option value="">Select</option><option value="Exide Care Signage">Exide Care Signage</option><option value="Non-EC Authorized Dealer Signage">Non-EC Authorized Dealer Signage</option><option value="No">No</option>');
        		    $('select[name="signage_type_2st_intallation"]').html('<option value="">Select</option><option value="Non-EC Authorized Dealer Signage">Non-EC Authorized Dealer Signage</option>');
        		
        			$('#dealer_code').val('');
        		    $('#dealer_name').val('');
    
        		    $('#dealer_code').attr('readonly', true);
        		    $('#dealer_name').attr('readonly', true);
        		    
        		    $('select[name="ec_status"]').html('<option value="">Select</option><option value="No">No</option><option value="Yes">Yes</option>');
        		    
        		    $('select[name="category"]').html('<option value="PB-P">PB-P</option><option value="PB-D">PB-D</option><option value="PB-G">PB-G</option><option value="PB-S">PB-S</option><option value="PB-B">PB-B</option><option value="PB-T">PB-T</option><option value="Non-PB">Non-PB</option>');
        		} else if(partner_name =='Sub Dealer') {
        		    $('select[name="signage_type_1st_intallation"]').html('<option value="">Select</option><option value="Humsafar Signage">Humsafar Signage</option><option value="No">No</option>');
        		    $('select[name="signage_type_2st_intallation"]').html('<option value="">Select</option><option value="Humsafar Signage">Humsafar Signage</option>');
                    
                    $('#dealer_code').attr('readonly', false);
                    $('#dealer_name').attr('readonly', false);
                    
                    $('select[name="ec_status"]').html('<option value="No">No</option>');
                    
                    $('select[name="category"]').html('<option value="">Select</option><option value="Cat A">Cat A</option><option value="Cat B">Cat B</option><option value="Cat C">Cat C</option><option value="Cat D">Cat D</option><option value="Cat E">Cat E</option><option value="No Cat">No Cat</option>');
                    
        		}
    		<?php  } else if($this->session->userdata('brand') == 'SF'){  ?>
    		
    		    if(partner_name =='Dealer'){

        			$('#dealer_code').val('');
        		    $('#dealer_name').val('');
    
        		    $('#dealer_code').attr('readonly', true);
        		    $('#dealer_name').attr('readonly', true);
        		    
        		    $('select[name="ec_status"]').html('<option value="">Select</option><option value="No">No</option><option value="Yes">Yes</option>');
        		    
        		    $('select[name="category"]').html('<option value="Direct Dealer">Direct Dealer</option><option value="Authorized Distributor">Authorized Distributor</option>');
        		} else if(partner_name =='Registered Retailer') {

                    $('#dealer_code').attr('readonly', false);
                    $('#dealer_name').attr('readonly', false);
                    
                    $('select[name="ec_status"]').html('<option value="No">No</option>');
                    
                    $('select[name="category"]').html('<option value="">Select</option><option value="Cat A">Cat A</option><option value="Cat B">Cat B</option><option value="Cat C">Cat C</option><option value="Cat D">Cat D</option><option value="Cat E">Cat E</option><option value="No Cat">No Cat</option>');
                    
        		}

    		<?php  } ?>
    	});
    	
    	$('select[name="signage_type_1st_intallation"]').on('change',function() {
    		var signage_type_1st_intallation = $(this).val();
    		
    		if(signage_type_1st_intallation == 'No'){
    		    $("#1st_installation_date").prop('disabled', true);
    		    $("#2nd_installation_date").prop('disabled', true);
    		    
    		    $('select[name="signage_type_2st_intallation"]').attr('disabled','disabled'); 
    		    
    		} else {
    		    $("#1st_installation_date").prop('disabled', false);
    		    $("#2nd_installation_date").prop('disabled', false);
    		    
    		    $('select[name="signage_type_2st_intallation"]').removeAttr('disabled');   
    		}
    		if(signage_type_1st_intallation == ''){
    			$('select[name="signage_type_2st_intallation"]').val("");
    			
    		}
    	});
    	
    	
    	$('select[name="in_shop_branding_status_1st_installation"]').on('change',function() {
    		var in_shop_branding_status_1st_installation = $(this).val();
    		
    		if(in_shop_branding_status_1st_installation == 'No'){
    		    $("#in_shop_branding_1st_date").prop('disabled', true);
    		    $('select[name="in_shop_branding_status_2st_installation"]').attr('disabled','disabled'); 
    		    $("#in_shop_branding_2nd_date").prop('disabled', true);
    		} else {
    		    $("#in_shop_branding_1st_date").prop('disabled', false);
    		    $('select[name="in_shop_branding_status_2st_installation"]').removeAttr('disabled');   
    		    $("#in_shop_branding_2nd_date").prop('disabled', false);
    		}
    	});
    	
    	
    	$('select[name="in_shop_branding_status_2st_installation"]').on('change',function() {
    		var in_shop_branding_status_2st_installation = $(this).val();
    		
    		if(in_shop_branding_status_2st_installation == 'No'){
    		    $("#in_shop_branding_2nd_date").prop('disabled', true);
    		   
    		} else {
    		    $("#in_shop_branding_2nd_date").prop('disabled', false);
    		}
    	});
        
        
		$('select[name="tmm_zone"]').on('change', function() {
			var zone = $(this).val();
			var post_data = {
			'zone': zone,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
			};
			if(zone) {
				$.ajax({
					url:'<?php echo base_url('users/loadRegion') ?>',
					type: "POST",
					data:post_data,
					dataType: "json",
					success:function(data) 
					{
						console.log(data);
						$('select[name="region"]').empty();
						$.each(data, function(key, value) {
							$('select[name="region"]').append('<option value="'+ value.region +'">'+ value.region +'</option>');
						});
						$('.chosen-select').trigger('chosen:updated');
					}
				});
			}else{
				$('select[name="region"]').empty();
			}
		});
		
		/* Select State */
		$('select[name="state"]').on('change', function() {
			var state = $(this).val();
			var post_data = {
			'state': state,
			'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
			};
			if(state) {
				$.ajax({
					url:'<?php echo base_url('users/loadCity') ?>',
					type: "POST",
					data:post_data,
					dataType: "json",
					success:function(data) 
					{
						console.log(data);
						$('select[name="city"]').empty();
						$.each(data, function(key, value) {
							$('select[name="city"]').append('<option value="'+ value.cityname +'">'+ value.cityname +'</option>');
						});
						$('.chosen-select').trigger('chosen:updated');
					}
				});
			}else{
				$('select[name="city"]').empty();
			}
		});
	});
</script>
<script>
$().ready(function() {		
// validate signup form on keyup and submit	
$.validator.setDefaults({ ignore: ":hidden:not(select)" })

$("#add_user").validate({			
    rules: {
        sap_code: {
        required:true,	
        remote: {
			url: '<?php echo base_url('store/check_sapCode') ?>',
			type: "post",
			data: {
				sap_code: function(){ return $("#sap_code").val(); 
			    
				},
				"<?php echo $this->security->get_csrf_token_name(); ?>": "<?php echo $this->security->get_csrf_hash(); ?>"
			    }
		    }
        }, 
        store_name: "required",			
        name: "required",			
        mobile: {
            required: true,
            digits: true
        },
        
        pincode:{
            required:true,
            digits: true,
            minlength: 6,
            maxlength: 6,
        },
        
        state:"required",
        address:"required"
    },	
    
    errorPlacement: function(error, element) {
        if (element.attr("name") == "state") {
            error.insertAfter("#state_chosen");
        }else{
            error.insertAfter(element);
        }
        
        /*if (element.attr("name") == "zone") {
            error.insertAfter(".chosen-container-single");
        }else{
            error.insertAfter(element);
        }*/
    },
    
messages: {				
    store_name: "Please enter Store Name",			
    name: "Please enter Contact Person Name",				
    mobile: "Please enter Mobile Number",
    mobile: {
			required: "Please enter Mobile Number",
			digits: "Please enter Valid Mobile Number"
	},
	sap_code: {
            remote:"SAP Code already exist",
            required:"Please enter SAP Code"
    },
    pincode:{ 
        required:"Please enter Pincode",
        digits: "Please enter Valid Pincode"
    },
    state: "Please enter State",
    address: "Please enter Address 1",
    }
    });	
});	
</script>
