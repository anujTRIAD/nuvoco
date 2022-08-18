
<div class="content-wrapper">
    <section class="content-header">
        <a href="<?php echo base_url('store'); ?>" class="pull-right btn btn-info"><i class="fa fa-arrow"></i>Back</a>
        <h1><?php echo $page_title; ?></h1>
    </section>
	    <section class="content">
	        <div class="row">
	        	<div class="col-md-6">
	        		<?php echo get_flashdata('message'); ?>
		            <div class="modal-content">
		                <form action="<?php echo base_url('store/upload_store'); ?>" method="post" id="add_user" enctype="multipart/form-data">
		                <div class="modal-body">
							<div class="row">
								<div class="form-group col-sm-6">
									<label>File<span class="red"> *</span></label>
									<input type="file" class="form-control" id="file" name="file" placeholder="Select Image" required="">
									<a href="<?php echo base_url('data/store_csv/bulk_store_upload_sample.csv');?>"><i class="fa fa-download"></i> Download Sample File</a>
								</div>
							</div>
		                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		                </div>      
		                <div class="modal-footer center">
		                    <button type="submit" class="btn btn-success" name="submit"><i class="fa fa-save"></i> Create</button>
		                </div>
		                </form>
			        </div>
		        </div>
		        <div class="col-md-6">
		        	<div class="alert alert-info alert-dismissible">
		                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&#x2716;</button>
		                <h4><i class="icon fa fa-warning"></i> Format Guide !</h4>
		                <b>Please make sure the data in .csv file is in the following format</b>
		                <p>It must contain 25 columns in the following order</p>
		                <ul>
		                    <li>Type of Channel Partner  (<span class="red">Mandatory</span>)</li> 
		                    <li>SAP Code (<span class="red">Mandatory</span>)</li> 
		                	<li>Store Name (<span class="red">Mandatory</span>)</li>
		                	<li>Dealer Code (<span class="red">Mandatory if Type of Channel Partner is SubDealer</span>)</li>
		                	<li>Dealer Name (<span class="red">Mandatory if Type of Channel Partner is SubDealer</span>)</li>		                	
		                	<li>Tmm Zone (<span class="red">Mandatory</span>)</li>
		                	<li>Region (<span class="red">Mandatory</span>)</li>
		                	<li>Hub (<span class="red">Mandatory</span>)</li>
		                	<li>Spoke (<span class="red">Mandatory</span>)</li>
		                	<li>Address Line 1 ( <span class="red">Mandatory</span> )</li>
		                	<li>Address Line 2 </li>
		                	<li>State </li>
		                	<li>City </li>
		                	<li>Pincode ( <span class="red">Mandatory</span> )</li>
		                	<li>Contact No / Mobile (<span class="red">Mandatory</span>)</li>
		                	<li>EC Status (<span class="red">Mandatory</span>)</li>
		                	<li>Category (<span class="red">Mandatory</span>)</li>
		                	
		                	<li>Signage Type (1st Intallation) </li>
		                	<li>Installation Date (<span class="red">Formate (Y-m-d)</span>)</li>
		                	
		                	<li>Signage Type (2nd Installation) </li>
		                	<li>Installation Date (<span class="red">Formate (Y-m-d)</span>)</li> 
		                	
		                	<li>In-shop Branding Status (1st Installation) </li>
		                	<li>In-shop Branding Date(<span class="red">Formate (Y-m-d)</span>)</li> 
		                	
		                	<li>In-shop Branding Status (2nd Installation) </li>
		                	<li>In-shop Branding Date (<span class="red">Formate (Y-m-d)</span>)</li> 
						</ul>
		            </div>
		        </div>
	        </div>
	        
	        <?php if($this->session->userdata('error_data')){  ?>
	        <div class="box">
	            <h3>Errors Found in Store Upload CSV</h3>
			<div class="box-body">
				<div class="row">
					    <?php
							$error = $this->session->userdata('error_data');
						?>
						<div class="col-xs-12 table-responsive">
							<table id="sellout_table" class="table table-striped table-bordered" data-page-length='10'>
								<thead>
									<tr>
										<th>SAP Code</th>
										<th>Store Name</th>
										<th>Tmm Zone</th>
										<th>Contact No</th>
										<th>Region</th>
										<th>Pincode</th>
										<th>Error Message</th>
									</tr>
								</thead> 
								<tbody>
									<?php foreach ($error as $data1) { ?>
									<tr>
										<td><?php echo $data1['sap_code'];?></td>
										<td><?php echo $data1['store_name'];?></td>
										<td><?php echo $data1['tmm_zone'];?></td>
										<td><?php echo $data1['Contact_No'];?></td>
										<td><?php echo $data1['region'];?></td>
										<td><?php echo $data1['Pincode'];?></td>
										<td style="color:red"><?php echo $data1['error_msg'];?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
				</div>
			</div>
		</div>
	   <?php } ?>     
	        
	    </section>
</div>
