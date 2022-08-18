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
    <a href="<?php echo base_url('element/download'); ?>" class="pull-right btn btn-info" style="margin-right: 20px;"><i class="fa fa-download"></i> Download</a>
		
		<a href="<?php echo base_url('vendor/upload_csv'); ?>" style="margin-right: 10px" class="pull-right btn btn-success"><i class="fa fa-upload"></i> Upload Element</a> 
    <a style="margin-right: 10px;" href="<?php echo base_url('element/add'); ?>" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> Add Element</a>
		<h1><?php echo $page_title; ?></h1>
	</section>

	<section class="content">
		<?php echo get_flashdata('message'); ?>
		<div class="box">  
			<div class="box-body"> 
        <div class="col-md-4"></div>
			<div class="col-xs-6 mx-auto" style="margin-top: 30px;">
				 <form action="<?php echo base_url('dashboard/add-role'); ?>" method="post">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Role Name</label>
                  <input type="text" name="name" class="form-control">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="">Slect Role Type</label>
                    <select name="type" id="type" class="form-control select">
                      <option value="NA" selected="selected" disabled="true">Select</option>
                      <option value="internal">Internal</option>
                      <option value="external">External</option>
                    </select>
                </div>
              </div>
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <div class="col-md-6 text-center">
                <button type="submit" name="submit" class="btn btn-primary" style="margin-top: 25px;"> <i class="fa fa-save"></i> Save</button>
              </div>
            </div>
         </form>
			</div>	
			</div>
		</div>
		
	</section>
</div>
<script src="https://vmxpro.com/exide_dev/assets/chosen/chosen.js"></script>
<script type="text/javascript">
$(document).ready(function($) {
   $('.select').chosen();
}); 
</script>