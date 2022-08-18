
<section class="content margin-custom">
	<div class="block-header">
		<div class="row">
			<div class="col-lg-9 col-md-6 col-sm-12">
				<h2> <?php echo $page_title; ?> </h2>
			</div>
			<div class="col-md-3">
				<a class="btn btn-simple text-white border-white float-right" href="<?php echo base_url('download_csv/user'); ?>"> <i class="zmdi zmdi-download"></i> Download </a>
				<!-- <a class="btn btn-simple text-white border-white float-right" href="<?php echo base_url('users'); ?>"> <i class="zmdi zmdi-arrow-back"></i> Back </a> -->
				<a class="btn btn-simple text-white border-white float-right mr-2" href="<?php echo base_url('users/add'); ?>"> <i class="zmdi zmdi-plus"></i> Add User </a>
			</div> 
		</div>
	</div>
	<div class="container-fluid">

		<div class="row clearfix">
			<div class="col-lg-12">
				<div class="card">
					<div class="body">
			 <div class="col-xs-12 table-responsive" style="margin-top: 30px;">
				<table id="users_table" class="table table-striped table-bordered js-basic-example dataTable">
					<thead>
						<tr>
							<th>User Id</th>
							<th>Role</th>
							<th>Zone</th>
							<th>State</th>
							<th>City</th>
							<th>Name</th>
							<th>Email</th>
							<th>Contact</th>
							<th>Username</th>
							<th>Status</th>
							
						</tr>
					</thead>

					<tbody id="data_table">
					</tbody>
					
				</table>
			</div>
		</div>
	</div>
</div>
</div>
 

	</div>
</section> 

<script type="text/javascript">

	$(document).ready(function() {

		//========================== Datatable server side code ===================
		
	
		render_table_data();
		
		function render_table_data() {
		
	   		$('.dataTable').DataTable({
				
	   					processing: true,
                        serverSide: true,
                      
                        ajax: {
                            url: "<?php echo base_url('users/data') ?>" ,
                            type: 'POST',
                            data:{
                                "<?php echo $this->security->get_csrf_token_name() ?>" :"<?php echo $this->security->get_csrf_hash()?>",
                              
                            }
                        }
                        
                        
	   			});
		
	   	}
	
	   
	


// changing active status
var onoffswitch_ajax = false;
$('#users_table').on("change", '.onoffswitch-checkbox', function() {
    var chkbox = $(this);
    var active = '';
    var id = chkbox.data('id');
    var value = (this.checked?'Approved':'Inactive');
    
    chkbox.attr({'disabled':'disabled'});
    chkbox.parent().css({'opacity':'0.4'})

    if( !onoffswitch_ajax ) {
        onoffswitch_ajax = true;
        $.ajax({
            url: "<?php echo base_url('users/status'); ?>",
            type: "POST",
            data: "id="+id+"&value="+value+"&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>",
            success: function(response) {
                // console.log(response);
                onoffswitch_ajax = false;
                chkbox.removeAttr('disabled');
                chkbox.parent().css({'opacity':'1'})
            }
        });
    }
});
});
</script>