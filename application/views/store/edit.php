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
                <form action="<?php echo base_url('store/edit_store/'.$this->uri->segment(3)); ?>" method="post" id="add_user" enctype="multipart/form-data">
                <div class="modal-body">
                	<div class="row">
						
						<div class="form-group col-sm-4">
							<label class="userlabel">Store Name <span class="red">*</span></label>
							<input type="text" class="form-control" id="store_name" name="store_name" value="<?php echo $stores[0]['store_name']; ?>" required>
						</div>
						<div class="form-group col-sm-4">
							<label class="userlabel">SAP CODE <span class="red">*</span></label>
							<input type="text" class="form-control" id="name" name="name" value="<?php echo $stores[0]['sap_code']; ?>" readonly>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-4">
							<label>Contact No / Mobile <span class="red">*</span></label>
							<input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $stores[0]['contact_no']; ?>" required minlength="10" maxlength="10" required="">
						</div>	
						
						<div class="form-group col-sm-4">
							<label>Zone </label>
							
							<select name="" class=" form-control zone" required="required" disabled>
								<option>Select Zone</option>
							
								<option value="" <?php echo ($stores[0]['tmm_zone'] == "East") ? 'selected' : ''; ?>>East</option>
								<option value="" <?php echo ($stores[0]['tmm_zone'] == "West") ? 'selected' : ''; ?>>West</option>
								<option value="" <?php echo ($stores[0]['tmm_zone'] == "North1") ? 'selected' : ''; ?>>North1</option>
								<option value="" <?php echo ($stores[0]['tmm_zone'] == "North2") ? 'selected' : ''; ?>>North2</option>
								<option value="" <?php echo ($stores[0]['tmm_zone'] == "South") ? 'selected' : ''; ?>>South</option>
							</select>            
						</div>
						<div class="form-group col-sm-4">
						    <label>Region </label>
							<select name="" class=" form-control region" disabled>
								<option value="" ><?php echo $stores[0]['region']; ?></option>
							</select>  
						</div>
					</div>
					
					<div class="row">
					    <!--<div class="form-group col-sm-4">
							<label><?php echo $status;?> <span class="red">*</span></label>
							
							<select name="ec_status" class="chosen-select form-control ec_status" required="required">
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>  
						</div>-->
					    
					    <!--<div class="form-group col-sm-4">
							<label>Category <span class="red">*</span></label>
							<select name="category" id="category" class="chosen-select form-control" required >
								<option value="">Select</option>
								<?php if($this->session->userdata('brand') == 'Exide'){ ?>
    							    <option value="PB-T" <?php echo ($stores[0]['category'] == 'PB-T') ? 'selected' : ''; ?>>PB-T</option>
                                    <option value="PB-P" <?php echo ($stores[0]['category'] == 'PB-P') ? 'selected' : ''; ?>>PB-P</option>
                                    <option value="PB-D" <?php echo ($stores[0]['category'] == 'PB-D') ? 'selected' : ''; ?>>PB-D</option>
                                    <option value="PB-G" <?php echo ($stores[0]['category'] == 'PB-G') ? 'selected' : ''; ?>>PB-G</option>
                                    <option value="PB-S" <?php echo ($stores[0]['category'] == 'PB-S') ? 'selected' : ''; ?>>PB-S</option>
                                    <option value="PB-B" <?php echo ($stores[0]['category'] == 'PB-B') ? 'selected' : ''; ?>>PB-B</option>
                                    <option value="Non-PB" <?php echo ($stores[0]['category'] == 'Non-PB') ? 'selected' : ''; ?>>Non-PB</option>
                                    <option value="Cat A" <?php echo ($stores[0]['category'] == 'Cat A') ? 'selected' : ''; ?>>Cat A</option>
                                    <option value="Cat B" <?php echo ($stores[0]['category'] == 'Cat B') ? 'selected' : ''; ?>>Cat B</option>
                                    <option value="Cat C" <?php echo ($stores[0]['category'] == 'Cat C') ? 'selected' : ''; ?>>Cat C</option>
                                    <option value="Cat D" <?php echo ($stores[0]['category'] == 'Cat D') ? 'selected' : ''; ?>>Cat D</option>
                                <?php } else if($this->session->userdata('brand') == 'SF'){?>
                                
                                    <option value="Authorized Distributor" <?php echo ($stores[0]['category'] == 'Authorized Distributor') ? 'selected' : ''; ?>>Authorized Distributor</option>
                                    <option value="Direct Dealer" <?php echo ($stores[0]['category'] == 'Direct Dealer') ? 'selected' : ''; ?>>Direct Dealer</option>
                                    <option value="Cat A" <?php echo ($stores[0]['category'] == 'Cat A') ? 'selected' : ''; ?>>Cat A</option>
                                    <option value="Cat B" <?php echo ($stores[0]['category'] == 'Cat B') ? 'selected' : ''; ?>>Cat B</option>
                                    <option value="Cat C" <?php echo ($stores[0]['category'] == 'Cat C') ? 'selected' : ''; ?>>Cat C</option>
                                    <option value="Cat D" <?php echo ($stores[0]['category'] == 'Cat D') ? 'selected' : ''; ?>>Cat D</option>
                               <?php } ?>        
                           </select>
						</div>-->
						
					</div>
				
					<div class="row">	
						<div class="form-group col-sm-4">
							<label>Address Line 1<span class="red">*</span></label>
							<textarea name="address" id="address" cols="30" rows="2"  class="form-control" required="required"><?php echo $stores[0]['address']; ?></textarea>
						</div>	
						<div class="form-group col-sm-4">
							<label>Address Line 2 </label>
							<textarea name="address2" id="address2" cols="30" rows="2"  class="form-control" ><?php echo $stores[0]['address2']; ?></textarea>
						</div>
						<div class="form-group col-sm-4">
							<label>Pincode  </label>
							<textarea name="pincode" id="pincode" cols="30" rows="2"  class="form-control" ><?php echo $stores[0]['pincode']; ?></textarea>
						</div>
					
					</div>
					<?php if($this->session->userdata('brand') == 'Exide'){  ?>
					<div class="row">
						<div class="form-group col-sm-6">
							<label>Signage Type (1st Installation) </label>
						    <select name="signage_type_1st_intallation" class="chosen-select form-control signage_type_1st_intallation" required="required">
							</select>
						</div>
						
						<div class="input-group date col-sm-6">
					        <label>Installation Date </label>
							<input type="text" name="1st_installation_date" id="1st_installation_date" readonly class="form-control" value="<?php echo $stores[0]['1st_installation_date']; ?>" required="" />
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
						
					</div>

					<div class="row">
						<div class="form-group col-sm-6">
							<label>Signage Type (2nd Installation) </label>
							<select name="signage_type_2st_intallation" class="chosen-select form-control signage_type_2st_intallation" >
								<!--<option value="Exide Care Signage">Exide Care Signage</option>
								<option value="Non-EC Authorized Dealer Signage">Non-EC Authorized Dealer Signage</option>
								<option value="Humsafar Signage">Humsafar Signage</option>
								<option value="Signage not installed"> Signage not installed</option>-->
							</select>
						</div>
						
						<div class="input-group date col-sm-6">
					        <label>Installation Date </label>
							<input type="text" name="2nd_installation_date" id="2nd_installation_date" readonly class="form-control" value="<?php echo $stores[0]['2nd_installation_date']; ?>"/>
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
					
					<div class="row">
					    <div class="form-group col-sm-6">
							<label>In-shop Branding Status (1st Installation) <span class="red">*</span></label>
						    <select name="in_shop_branding_status_1st_installation" class="chosen-select form-control in_shop_branding_status_1st_installation" >
								<option value="">Select</option>
								<option value="Yes" <?php echo $store[0]['in_shop_branding_status_1st_installation'] == 'Yes' ? "selected":"";?>>Yes</option>
								<option value="No" <?php echo $store[0]['in_shop_branding_status_1st_installation'] == 'No' ? "selected":"";?>>No</option>
							</select>
						
						</div>
					    
					    <div class="input-group date col-sm-6">
					        <label>In-shop Branding Date <span class="red">*</span></label>
							<input type="text" name="in_shop_branding_1st_date" id="in_shop_branding_1st_date" readonly class="form-control" value="<?php echo $store[0]['in_shop_branding_1st_date'];?>" />
							
						</div>
					</div>
					
					<div class="row">	
						<div class="form-group col-sm-6">
							<label>In-shop Branding Status (2nd Installation) <span class="red">*</span></label>
						    <select name="in_shop_branding_status_2st_installation" class="chosen-select form-control in_shop_branding_status_2st_installation" >
								<option value="">Select</option>
								<option value="Yes" <?php echo $store[0]['in_shop_branding_status_2st_installation'] == 'Yes' ? 'selected':'';?>>Yes</option>
								<option value="No" <?php echo $store[0]['in_shop_branding_status_2st_installation'] == 'No' ? 'selected':'';?>>No</option>
							</select>
						</div>
					    
					    <div class="input-group date col-sm-6">
					        <label>In-shop Branding Date <span class="red">*</span></label>
							<input type="text" name="in_shop_branding_2nd_date" id="in_shop_branding_2nd_date" readonly class="form-control" value="<?php echo $store[0]['in_shop_branding_2nd_date'];?>"/>
								
						</div>
					</div>
					<?php } ?>
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                </div>      
                <div class="modal-footer center">
                    <button type="submit" class="btn btn-success" name="submit"><i class="fa fa-refresh"></i> Update</button>
                </div>
                </form>
            </div>
        </div>
    </section>
</div>
<script>
$(document).ready(function() {
var channel_partner_type = '<?php echo $stores[0]["channel_partner_type"]?>';
var signage_type_1st_intallation = '<?php echo $stores[0]["signage_type_1st_intallation"]?>';
var signage_type_2st_intallation = '<?php echo $stores[0]["signage_type_2st_intallation"]?>';
signage_options(channel_partner_type , signage_type_1st_intallation, signage_type_2st_intallation);

function signage_options(type , signage_1st , signage_2nd) {
	if(type == 'Dealer'){
		$('select[name="signage_type_1st_intallation"]').html(`<option value="">Select</option><option value="Exide Care Signage" ${signage_1st == 'Exide Care Signage' ? 'selected' : ''}>Exide Care Signage</option><option value="Non-EC Authorized Dealer Signage" ${signage_1st == 'Non-EC Authorized Dealer Signage' ? 'selected' : ''}>Non-EC Authorized Dealer Signage</option><option value="No" ${signage_1st == 'No' ? 'selected' : ''}>No</option>`);

	    $('select[name="signage_type_2st_intallation"]').html(`<option value="">Select</option><option value="Non-EC Authorized Dealer Signage" ${signage_2nd == 'Non-EC Authorized Dealer Signage' ? 'selected' : ''}>Non-EC Authorized Dealer Signage</option>`);
	}else{
		$('select[name="signage_type_1st_intallation"]').html(`<option value="">Select</option><option value="Humsafar Signage" ${signage_1st == 'Humsafar Signage' ? 'selected' : ''}>Humsafar Signage</option><option value="No" ${signage_1st == 'No' ? 'selected' : ''}>No</option>`);

	    $('select[name="signage_type_2st_intallation"]').html(`<option value="">Select</option><option value="Humsafar Signage" ${signage_2nd == 'Humsafar Signage' ? 'selected' : ''}>Humsafar Signage</option>`);
	}
}
$('select[name="signage_type_1st_intallation"]').on('change',function() {
	var signage_type_1st_intallation = $(this).val();
	if(signage_type_1st_intallation == ''){
		$('select[name="signage_type_2st_intallation"]').val("");
		
	}
});
// validate signup form on keyup and submit	
$.validator.setDefaults({ ignore: ":hidden:not(select)" })

$("#add_user").validate({			
    rules: {
        store_name: "required",			
        name: "required",			
        mobile: {
            required: true,
            digits: true
        },
        
        pincode:"required",
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
    store_uniqueID: "Please enter Store ID",				
    store_name: "Please enter Store Name",			
    name: "Please enter Contact Person Name",				
    mobile: "Please enter Mobile Number",
    mobile: {
			required: "Please enter Mobile Number",
			digits: "Please enter Valid Mobile Number"
	},
    pincode: "Please enter Pincode",
    state: "Please enter State",
    address: "Please enter Address 1",
    }	
    });	
});	
</script>
