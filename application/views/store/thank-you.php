<div class="content-wrapper">
	<section class="content">
		<div class="box">
			<div class="box-body">
				<div class="row text-center">
					<div class="col-md-12">
						<div class="lockscreen-wrapper">
							<?php if(get_flashdata('message')!=''){ ?>
							    <div class="lockscreen-logo">
							    	<?php echo get_flashdata('message'); ?>
								</div>
							<?php } ?>
							  <div class="lockscreen-item">
									<a href="<?php echo base_url('store/add_store');?>"><button class="btn btn-primary"> <i class="fa fa-arrow-left"></i> Add More Store</button></a>
									<a href="<?php echo base_url('store');?>"><button class="btn btn-primary"> <i class="fa fa-arrow-right"></i> Store List</button></a>
							  </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>