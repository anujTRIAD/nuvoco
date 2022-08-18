<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url('assets/history-files/style-history.css'); ?>"> <!-- Resource style -->
<script src="<?php echo base_url('assets/history-files/modernizr.js'); ?>"></script> 
<style type="text/css">
body .container {
	min-width: 99%;
}

.table tbody tr th {
	background-color: #efefef;
}

.table-bordered,
.table-bordered td,
.table-bordered th {
	border: none;
}

.ticket_title {
	display: block;
	font-size: 17px;
	font-weight: 600;
}

.ticket_table tbody tr th,
.ticket_table tbody tr td {
	border: none;
	background: transparent;
	line-height: 45px;
	color: #6B6F82;
}

.ticket_table tbody tr {
	border-bottom: 2.5px solid #ccc;
}

.ticket_table tbody tr th {
	font-weight: 700;
	font-size: 15px;
}

.ticket_table tbody tr td {
	font-size: 15px;
}

.ticket_table tbody tr td .avatar {
	margin-right: 3px;
}

.ticket_tbs li a {
	font-size: 16px;
	color: #28d094;
}

.ticket_tbs li a:hover {
	color: green
}

.ticket_tbs li.active a {
	color: #6B6F82 !important;
	font-weight: 600;
}

.ticket_category span {
	border: 1px solid #1e9ff2;
	padding: 3px 6px;
	border-radius: 7px;
	color: #1e9ff2;
}

.detail_in {
	width: 100%;
	height: auto;
	float: left;
	border: 1px solid #ccc;
	border-radius: 5px;
	margin-top: 20px;
}

.detail_in .la {
	font-size: 1.8rem;
	font-weight: 600;
}

.detail_heading {
	margin-top: 5px;
	color: #557280;
	font-size: 18px;
}

.detail_in tr {
	border-bottom: 1px solid #ccc;
}

.detail_in tr:nth-child(odd) {
	background: #f7f7f7;
}

.detail_in tr:last-child {
	border-bottom: transparent;
}

.detail_in table th {
	width: 30%;
	text-align: center;
	color: #5f6160;
}

.detail_in table td {
	padding: 5px;
	text-align: center;
}

.saparator {
	width: 30%;
	height: 4px;
	background: #b9bdbd;
	border-radius: 50px;
	margin: auto;
	margin-bottom: 15px;
}

.open_status {
	color: red;
	border: 1px solid red;
	border-radius: 4px;
	padding: 2px 10px;
}

.panding_status {
	color: yellow;
}

.closed_status {
	color: green;
}


/*.bd_2 .saparator{ background: #4f5290b5;  }
.bd_3 .saparator{ background: #1b749e;  }
*/

.desc_data {
	padding-top: 10px;
	padding-bottom: 10px;
}

#server-processing {
	position: relative;
}

.status_img {
	float: right;
	position: absolute;
	z-index: 10;
	right: 19px;
	top: 10px;
}

.remarks_form {
	background: #f1f1f1;
	padding: 10px 20px;
	border-radius: 10px;
}

.remarks_form form lable {
	color:
}

.ticekt_id {
	width: 300px;
	height: auto;
	position: absolute;
	z-index: 10;
	left: 38%;
	top: 13px;
}

.ticekt_id .ticket_date,
.ticekt_id .ticketInner_id {
	width: 50%;
	float: left;
	text-align: center;
	font-size: 14px;
	color: #6B6F82;
	font-weight: 600;
}

.blue_cl {
	color: #0C84D1 !important;
	font-weight: 500;
}
</style>
<div class="app-content container center-layout mt-2">
   <div class="content-wrapper">
      <div class="content-header row"></div>
      <div class="content-body">
         <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2">
               <h1 class="content-header-title mb-0">Tickets</h1>
            </div>
         </div>
         <!-- The Modal -->
         <?php
         	if($ticket_details["status"] == 'Closed. No action taken by AM') {
         		$status = 'Closed';
         	} else {
         		$status = $ticket_details["status"];
         	}
         ?>
         <section id="server-processing">
            <img src="<?php echo base_url('assets/images/icons/').$status.'.png'; ?>" class="status_img" width="100px;">
            <div class="ticekt_id">
               <div class="ticket_date">
                  <div>Ticket Date:</div>
                  <div class="blue_cl"><?php echo $ticket_details['dt'] ?></div>
               </div>
               <div class="ticketInner_id">
                  Ticketr ID: 
                  <div  class="blue_cl"> <?php echo $ticket_details['ticket_id'] ?></div>
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  <div class="card">
                     <div class="card-content collpase">
                        <div class="card-body card-dashboard">
                           <ul class="nav nav-tabs ticket_tbs">
                              <li class="active" ><a href="#home" data-toggle="pill" > Ticket Details</a></li>
                              <li ><a data-toggle="pill" href="#home12" > Ticket History</a></li>
                           </ul>
                           <div class="tab-content">
                              <div id="home" class="tab-pane fade in active " >
                                 <div class="row">
                                    <div class="col-md-4 ">
                                       <div class="detail_in ">
                                          <div class="detail_heading text-center"><i class="la la-ticket"></i> Ticket</div>
                                          <div class="saparator"></div>
                                          <table width="100%" class="table-hover">
                                             <tr>
                                                <th>Subject</th>
                                                <td>:</td>
                                                <td><?php echo $ticket_details['subject'] ?></td>
                                             </tr>
                                             <tr>
                                                <th>Status</th>
                                                <td>:</td>
                                                <td><?php echo $ticket_details['status'] ?></td>
                                             </tr>
                                             <tr>
                                                <th><?php echo dp('kre', 2) ?> Code</th>
                                                <td>:</td>
                                                <td><?php echo $ticket_details['uniquecode'] ?></td>
                                             </tr>
                                             <tr>
                                                <th><?php echo dp('kre', 2) ?> Name</th>
                                                <td>:</td>
                                                <td><?php echo $ticket_details['are_name'] ?></td>
                                             </tr>
                                             <tr>
                                                <th>Store ID</th>
                                                <td>:</td>
                                                <td><?php echo $ticket_details['store_id'] ?></td>
                                             </tr>
                                          </table>
                                       </div>
                                    </div>
                                    <div class="col-md-4 ">
                                       <div class="detail_in bd_3 ">
                                          <div class="detail_heading text-center"><i class="la la-info-circle"></i> Ticket Description </div>
                                          <div class="saparator"></div>
                                          <div class="desc_data text-center"><?php echo $ticket_details['description']; ?>
                                          </div>
                                       </div>
                                       <div class="detail_in bd_3 ">
                                          <div class="detail_heading text-center"><i class="la la-archive"></i> Product Details</div>
                                          <div class="saparator"></div>
                                          <table width="100%" class="table-hover">
                                          	<?php if(is_array($deletion_stock_detail) && count($deletion_stock_detail) > 0) {
								  					foreach ($deletion_stock_detail as $key => $deletion_stock_dtl) {
								  			?>
	                                             <tr>
	                                                <th>Product Category</th>
	                                                <td>:</td>
	                                                <td class="ticket_category"><span><?php echo $deletion_stock_dtl['product_category']; ?></span></td>
	                                             </tr>
	                                             <tr>
	                                                <th>DSN 1</th>
	                                                <td>:</td>
	                                                <td><?php echo $deletion_stock_dtl['dsn']; ?></td>
	                                             </tr>
	                                             <tr>
	                                                <th>ASIN</th>
	                                                <td>:</td>
	                                                <td><?php echo $deletion_stock_dtl['asin']; ?></td>
	                                             </tr>
	                                        <?php } } ?>
                                          </table>
                                       </div>
	                                    <div class="detail_in bd_3 ">
                                          <div class="detail_heading text-center"><i class="la la-warning"></i> Ticket Reason </div>
                                          <div class="saparator"></div>
                                          <div class="desc_data text-center"><?php echo $ticket_details['reason']; ?>
                                          </div>
                                       	</div>
                                    </div>
                                    <div class="col-md-4 ">
                                       <div class="detail_in bd_2 ">
                                          <div class="detail_heading text-center"><i class="la la-cart-arrow-down"></i> Store Details</div>
                                          <div class="saparator"></div>
                                          <table width="100%" class="table-hover">
                                          	<?php if(is_array($store_details) && count($store_details) > 0) {
								  			foreach ($store_details as $key => $store_dtls) {

								  				$mapping_dtls = json_decode($store_dtls['mapping'], true);
								  			?>
	                                             <tr>
	                                                <th>Store ID</th>
	                                                <td>:</td>
	                                                <td><?php echo $ticket_details['store_id']; ?></td>
	                                             </tr>
	                                             <tr>
	                                                <th>Store Name  </th>
	                                                <td>:</td>
	                                                <td><?php echo $store_dtls['name']; ?></td>
	                                             </tr>
	                                             <tr>
	                                                <th>Store Type  </th>
	                                                <td>:</td>
	                                                <td><?php echo $store_dtls['store_type']; ?></td>
	                                             </tr>
	                                             <tr>
	                                                <th>Trade Type  </th>
	                                                <td>:</td>
	                                                <td><?php echo $store_dtls['trade_type']; ?></td>
	                                             </tr>
	                                             <tr>
	                                                <th>Zone    </th>
	                                                <td>:</td>
	                                                <td><?php echo $store_dtls['zone']; ?></td>
	                                             </tr>
	                                             <tr>
	                                                <th>State   </th>
	                                                <td>:</td>
	                                                <td><?php echo $store_dtls['state']; ?></td>
	                                             </tr>
	                                             <tr>
	                                                <th>City</th>
	                                                <td>:</td>
	                                                <td><?php echo $store_dtls['city']; ?></td>
	                                             </tr>
	                                             <tr>
	                                                <th>AM Code</th>
	                                                <td>:</td>
	                                                <td><?php echo $mapping_dtls['am_code'][0]; ?></td>
	                                             </tr>
	                                             <tr>
	                                                <th>AM Name</th>
	                                                <td>:</td>
	                                                <td><?php echo $mapping_dtls['am_name'][0]; ?></td>
	                                             </tr>
	                                        <?php } } ?>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 <?php if($ticket_details['status'] != 'Closed' || $ticket_details['status'] != 'Closed. No action taken by AM') { ?>
	                                 <div class="row mt-4">
	                                    <div class="col-md-6 col-md-offset-3 remarks_form">
	                                       <form role="form" action="<?php echo base_url("support-engine/stock_deletion/update-ticket-status/".$this->input->get('txn')); ?>" method="POST">
	                                          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
	                                          <div class="form-group">
	                                             <label for="status">Status</label>
	                                             <select name="status" id="status" class="form-control" required>
													<?php if($this->session->userdata('role') == "am"){ ?>
														<option value="" disabled selected>--Select Status--</option>
														<option value="Approved" <?php if($ticket_details["status"] == 'Approved') { echo 'selected="selected"'; } ?> >Approved</option>
														<option value="Not Approved" <?php if($ticket_details["status"] == 'Not Approved') { echo 'selected="selected"'; } ?> >Not Approved</option>
													<?php } else { ?>
														<option value="" disabled selected>--Select Status--</option>
														<option value="Open" <?php if($ticket_details["status"] == 'Open') { echo 'selected="selected"'; } ?>>Open</option>
														<option value="Pending" <?php if($ticket_details["status"] == 'Pending') { echo 'selected="selected"'; } ?>>Pending</option>
														<option value="Closed" <?php if($ticket_details["status"] == 'Closed') { echo 'selected="selected"'; } ?>>Closed</option>
														<option value="Closed. No action taken by AM" <?php if($ticket_details["status"] == 'Closed. No action taken by AM') { echo 'selected="selected"'; } ?>>Closed. No action taken by AM</option>
													<?php } ?>
												  </select>
	                                          </div>
	                                          <div class="form-group">
	                                             <label for="remarks">Remarks</label>
	                                             <textarea name="remarks" id="remarks" class="form-control" style="resize: vertical;" placeholder="Enter remarks" required=""><?=$ticket_details["remarks"]?></textarea>
	                                          </div>
	                                          <div class="form-group text-center">
	                                             <button class="btn btn-primary" type="submit" name="submit">Update</button>
	                                          </div>
	                                       </form>
	                                    </div>
	                                 </div>
	                             <?php } ?>
                              </div>
                              <div id="home12" class="tab-pane fade in" >
                                 <div class="col-md-10 col-md-offset-1">
                                    <section class="cd-horizontal-timeline">
									   <div class="timeline">
									      <div class="events-wrapper">
									         <div class="events">
									            <ol>
									            	<?php $i=0;
									            			if(is_array($ticket_logs) && count($ticket_logs) > 0) {

									            				foreach ($ticket_logs as $key => $value) {
												            		$date = date('d/m/Y', strtotime($value['date']));
												            		$dateShow = date('d-M', strtotime($value['date']));
												            		if($i==0) {
									            	?>
										               <li><a href="#0" data-date="<?php echo $date; ?>" class="selected"><?php echo $dateShow; ?></a></li>
										            <?php } else { ?>
										            	<li><a href="#0" data-date="<?php echo $date; ?>"><?php echo $dateShow; ?></a></li>
										            <?php } $i++; } } ?>
									            </ol>
									            <span class="filling-line" aria-hidden="true"></span>
									         </div>
									      </div>
									      <ul class="cd-timeline-navigation">
									         <li><a href="#0" class="prev inactive">Prev</a></li>
									         <li><a href="#0" class="next">Next</a></li>
									      </ul>
									   </div>
									   <div class="events-content">
									      <ol>
									      		<?php $i=0;
								            			if(is_array($ticket_logs) && count($ticket_logs) > 0) {
								            				foreach ($ticket_logs as $key => $value) {
												      			$date = date('d/m/Y', strtotime($value['date']));
												      			$dateShow = date('d-M', strtotime($value['date']));
											            		if($i==0) {
								            	?>
										         <li class="selected" data-date="<?php echo $date; ?>">
										            <blockquote class="blockquote border-0">
										               <div class="media">
										                  <div class="media-left">
										                     <img class="media-object img-xl mr-1" src="https://pixinvent.com/modern-admin-clean-bootstrap-4-dashboard-html-template/app-assets/images/portrait/small/avatar-s-5.png" alt="Generic placeholder image">
										                  </div>
										                  <div class="media-body">
										                     <p>Sometimes life is going to hit you in the head with a brick. Don't lose faith.</p>
										                     <footer class="blockquote-footer text-left">Steve Jobs <br>
										                        <cite title="Source Title">Entrepreneur</cite>
										                     </footer>
										                  </div>
										               </div>
										            </blockquote>
										            <p class="mt-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum praesentium officia, fugit recusandae ipsa, quia velit nulla adipisci? Consequuntur aspernatur at.</p>
										         </li>
										        <?php } else { ?>
										        	<li data-date="<?php echo $date; ?>">
										            <blockquote class="blockquote border-0">
										               <div class="media">
										                  <div class="media-left">
										                     <img class="media-object img-xl mr-1" src="https://pixinvent.com/modern-admin-clean-bootstrap-4-dashboard-html-template/app-assets/images/portrait/small/avatar-s-5.png" alt="Generic placeholder image">
										                  </div>
										                  <div class="media-body">
										                     <p>Sometimes life is going to hit you in the head with a brick. Don't lose faith.</p>
										                     <footer class="blockquote-footer text-left">Steve Jobs <br>
										                        <cite title="Source Title">Entrepreneur</cite>
										                     </footer>
										                  </div>
										               </div>
										            </blockquote>
										            <p class="mt-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum praesentium officia, fugit recusandae ipsa, quia velit nulla adipisci? Consequuntur aspernatur at.</p>
										         </li>
										        <?php } $i++; } } ?>
									      </ol>
									   </div>
									</section>
                                 </div>
                                 <div class="clearfix"></div>
                                 <?php if($ticket_details['status'] != 'Closed' || $ticket_details['status'] != 'Closed. No action taken by AM') { ?>
	                                 <div class="row mt-4">
	                                    <div class="col-md-6 col-md-offset-3 remarks_form">
	                                       <form role="form" action="<?php echo base_url("support-engine/stock_deletion/update-ticket-status/".$this->input->get('txn')); ?>" method="POST">
	                                          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
	                                          <div class="form-group">
	                                             <label for="status">Status</label>
	                                             <select name="status" id="status" class="form-control" required>
													<?php if($this->session->userdata('role') == "am"){ ?>
														<option value="" disabled selected>--Select Status--</option>
														<option value="Approved" <?php if($ticket_details["status"] == 'Approved') { echo 'selected="selected"'; } ?> >Approved</option>
														<option value="Not Approved" <?php if($ticket_details["status"] == 'Not Approved') { echo 'selected="selected"'; } ?> >Not Approved</option>
													<?php } else { ?>
														<option value="" disabled selected>--Select Status--</option>
														<option value="Open" <?php if($ticket_details["status"] == 'Open') { echo 'selected="selected"'; } ?>>Open</option>
														<option value="Pending" <?php if($ticket_details["status"] == 'Pending') { echo 'selected="selected"'; } ?>>Pending</option>
														<option value="Closed" <?php if($ticket_details["status"] == 'Closed') { echo 'selected="selected"'; } ?>>Closed</option>
														<option value="Closed. No action taken by AM" <?php if($ticket_details["status"] == 'Closed. No action taken by AM') { echo 'selected="selected"'; } ?>>Closed. No action taken by AM</option>
													<?php } ?>
												  </select>
	                                          </div>
	                                          <div class="form-group">
	                                             <label for="remarks">Remarks</label>
	                                             <textarea name="remarks" id="remarks" class="form-control" style="resize: vertical;" placeholder="Enter remarks" required=""><?=$ticket_details["remarks"]?></textarea>
	                                          </div>
	                                          <div class="form-group text-center">
	                                             <button class="btn btn-primary" type="submit" name="submit">Update</button>
	                                          </div>
	                                       </form>
	                                    </div>
	                                 </div>
	                             <?php } ?>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
        </section>
      </div>
   </div>
</div>	

<script>
$(document).ready(function() {

	var datatable = $('#store_details').DataTable({
		"aoColumns": [
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null,
			null
		],
		"order": [],
		"paging": true,
		"lengthChange": true,
		"searching": true,
		"info": true,
		"autoWidth": false,
		language: {
        	searchPlaceholder: "Search Any Field"
    	}
	});

	var datatable = $('#ticekt_logs').DataTable({
		"aoColumns": [
			null,
			null,
			null,
			null,
			null
		],
		"order": [],
		"paging": true,
		"lengthChange": true,
		"searching": true,
		"info": true,
		"autoWidth": false,
		language: {
        	searchPlaceholder: "Search Any Field"
    	}
	});
	
});

$('.accordian-body').on('show.bs.collapse', function () {
    $(this).closest("table")
        .find(".collapse.in")
        .not(this)
        .collapse('toggle')
});
</script>

<script src="<?php echo base_url('assets/history-files/jquery-2.1.4.js'); ?>"></script>
<script src="<?php echo base_url('assets/history-files/jquery.mobile.custom.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/history-files/main.js'); ?>"></script>