<div class="container-fluid mt-auto">
	<div class="row clearfix">
		<div class="col-lg-12">
			<div class="card">
				<div class="body">
					<p class="m-b-0">© 2022 Triad Technologies</p>
				</div>
			</div>
		</div>
	</div>
 

<script src="<?php echo base_url('assets/bundles/morrisscripts.bundle.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/pages/charts/morris.js'); ?>"></script>
<script src="<?php echo base_url('assets/bundles/mainscripts.bundle.js'); ?>"></script>
<script type='text/javascript' src='https://vmxpro.com/exide_dev/assets/chart/fusion/fusioncharts.js'></script>

<!----------------DATA TABLE-------------------------->
<script src="<?php echo base_url('assets/bundles/datatablescripts.bundle.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/pages/tables/jquery-datatable.js'); ?>"></script>
 <!-- common js -->
  <script src="<?php echo base_url('assets/js/common.js')?>"></script>
  <!-- common js -->
<?php
		if( isset($inclusions['js']) ){
			foreach($inclusions['js'] as $js) {
				echo "<script type='text/javascript' src='".base_url($js.'.js')."'></script>\n";
			}
		}
	?>
<script>
	
	/*global $ */
	$(document).ready(function() {
		"use strict";
		$('.menu > ul > li:has( > ul)').addClass('menu-dropdown-icon');
		//Checks if li has sub (ul) and adds class for toggle icon - just an UI

		$('.menu > ul > li > ul:not(:has(ul))').addClass('normal-sub');

		$(".menu > ul > li").hover(function(e) {
			if ($(window).width() > 943) {
				$(this).children("ul").stop(true, false).fadeToggle(150);
				e.preventDefault();
			}
		});
		//If width is more than 943px dropdowns are displayed on hover    
		$(".menu > ul > li").click(function() {
			if ($(window).width() <= 943) {
				$(this).children("ul").fadeToggle(150);
			}
		});
		//If width is less or equal to 943px dropdowns are displayed on click (thanks Aman Jain from stackoverflow)

		$(".h-bars").click(function(e) {
			$(".menu > ul").toggleClass('show-on-mobile');
			e.preventDefault();
		});
		//when clicked on mobile-menu, normal menu is shown as a list, classic rwd menu story (thanks mwl from stackoverflow)

	});
</script>

<script>
	var fusioncharts = new FusionCharts({
		type: 'bar2d',
		renderAt: 'graph2',
		width: '100%',
		height: '340',
		dataFormat: 'json',
		dataSource: {
			"chart": {
				"caption": "Project By Status",
				"subCaption": "",
				"paletteColors": "#0075c2",
				"bgColor": "#ffffff",
				"showBorder": "0",
				"showCanvasBorder": "0",
				"usePlotGradientColor": "0",
				"plotBorderAlpha": "10",
				"placeValuesInside": "0",
				"valueFontColor": "#00000",
				"showAxisLines": "1",
				"axisLineAlpha": "25",
				"divLineAlpha": "10",
				"alignCaptionWithCanvas": "0",
				"showAlternateVGridColor": "0",
				"captionFontSize": "14",
				"subcaptionFontSize": "14",
				"subcaptionFontBold": "0",
				"toolTipColor": "#ffffff",
				"toolTipBorderThickness": "0",
				"toolTipBgColor": "#000000",
				"toolTipBgAlpha": "80",
				"toolTipBorderRadius": "2",
				"toolTipPadding": "5"
			},
			data: [{
					"label": "Allocated",
					"value": "70"
				},
				{
					"label": "Recee Underway",
					"value": "55"
				},
				{
					"label": "Deployment Underway",
					"value": "35"
				},
				{
					"label": "Deployed",
					"value": "25"
				},
				{
					"label": "Billed",
					"value": "12"
				}
			],

		}
	});
	fusioncharts.render();
</script>
<!-- notify message work -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $.notify.defaults({
        position: 'top right',
        style: 'bootstrap'
      });
      <?php if ($this->session->flashdata('success')) { ?>
        $.notify("<?php echo $this->session->flashdata('success'); ?>", "success");
      <?php }
      if ($this->session->flashdata('error')) { ?>
        $.notify("<?php echo $this->session->flashdata('error'); ?>", "error");
      <?php }
      if ($this->session->flashdata('warning')) { ?>
        $.notify("<?php echo $this->session->flashdata('warning'); ?>", "warn");
      <?php }
      if ($this->session->flashdata('info')) { ?>
        $.notify("<?php echo $this->session->flashdata('info'); ?>", "info");
      <?php } ?>
    });
  </script>
  <!-- notify message work -->

 
</body>

</html>
