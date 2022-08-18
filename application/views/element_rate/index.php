<style type="text/css">
table.dataTable thead .sorting {
    background-image: none;
}
table.dataTable thead .sorting_asc {
    background-image: none;
}
table.dataTable thead .sorting_desc {
    background-image: none;
}
</style>
<div class="content-wrapper">
	<section class="content-header">		
    <a href="<?php echo base_url('element-rate/download?state=').@$_GET['state'];?>" class="pull-right btn btn-info" style="margin-right: 20px;"><i class="fa fa-download"></i> Download</a> 
    <a style="margin-right: 10px;" href="<?php echo base_url('element/add'); ?>" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> Add Element Rate</a>
		<h1><?php echo $page_title; ?></h1>
	</section>

	<section class="content">
		<?php echo get_flashdata('message'); ?>
		<div class="box"> 
			<div class="box-body"> 
        <form action="<?php echo base_url('element-rate'); ?>" method="get" style="margin-top: 25px;">
        <div class="col-md-4">
          <label>Select State</label>
          <div class="form-group">
            <select name="state" id="state" class="form-control">
              <option value="Select" selected="selected" disabled="true">Select</option>
              <option value="<?php echo @'State_Name'; ?>" <?php echo (@$_GET['state'] == 'State_Name')?'selected':'';?>>State Name</option>
            </select> 
          </div>
        </div>
         
        <div class="col-md-2" style="margin-top: 24px;">
          <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Get Data</button>
          <a type="button" href="<?php echo base_url('element-rate')?>" class="btn btn-info" style="margin-left: 97px; margin-top: -58px; "><i class="fa fa-refresh" ></i> Clear</a>
        </div> 
      </form> 
			<div class="col-xs-12 table-responsive" style="margin-top: 30px;">
				<table id="element_rate_table" class="table table-striped table-bordered" data-page-length='10'>
					<thead>
						<tr>
							<th>State</th>
							<th>Element Id</th>
							<th>Element name</th>  
              <th>Rate</th>
              <th>Action</th> 
						</tr>
					</thead>
          <tbody>
            <?php if( is_array($users) && count($users) > 0 ) :?>
            <?php foreach( $users as $user ) : ?>
            <tr>
              <td><?php echo $user['name']; ?></td>
              <td><?php echo $user['username']; ?></td>
              <td><?php echo $user['role']; ?></td> 
              <td><?php echo $user['role']; ?></td> 
              <!-- <td class="center">
                <div class="onoffswitch">
                  <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch<?php //echo custom_encode($user['id']); ?>" data-id="<?php //echo custom_encode($user['id']); ?>" <?php //echo ($user['status']=='Approved'?'checked="checked"':''); ?>>
                  <label class="onoffswitch-label" for="myonoffswitch<?php //echo custom_encode($user['id']); ?>">
                    <span class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                  </label>
                </div>
              </td> -->
              <td >
                <a href="<?php echo base_url('reports/view/'.custom_encode($user['id'])); ?>" class="btn btn-primary btn-sm" title="View"><i class="fa fa-eye "></i></a>  
              </td>
            </tr>
          <?php endforeach; ?>
          <?php endif; ?>
          </tbody>
				</table>
			</div>	
			</div>
		</div>
		
	</section>
</div> 

<script src="https://vmxpro.com/exide_dev/assets/chosen/chosen.js"></script>
<script type="text/javascript">
$(document).ready(function($) {
  var datatable = $('#element_rate_table').DataTable({
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

// changing active status
var onoffswitch_ajax = false;
$('#element_rate_table').on("change", '.onoffswitch-checkbox', function() {
    var chkbox = $(this);
    var active = '';
    var id = chkbox.data('id');
    var value = (this.checked?'Approved':'Inactive');
    
    chkbox.attr({'disabled':'disabled'});
    chkbox.parent().css({'opacity':'0.4'})

    if( !onoffswitch_ajax ) {
        onoffswitch_ajax = true;
        $.ajax({
            url: "<?php echo base_url('element-rate/status'); ?>",
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
</script>