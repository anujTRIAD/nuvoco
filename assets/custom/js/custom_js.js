$(document).ready(function(e) {

	//fancybox popup
	$(document).on('click', '.fancybox',function(event) {
		var link = $(this).attr('href');
		var options = {
			href: link,
			padding: 0,
			autoHeight: true,
			autoCenter: true,
			openEffect : 'elastic',
			closeEffect : 'fadeout',
			closeClick  : false,
			helpers : { 
				overlay : { closeClick: false },
				overlay: { locked: false }
			},
		};

		if( $(this).hasClass('ajax') ) {
			options.type = "ajax";
		}

		if (typeof $(this).data('download') !== 'undefined') {
			var url = $(this).data('download');
			options.afterLoad = function() {
				this.title = '<a href="'+url+'"><i class="fa fa-download"></i> Download</a> ';
			};
		}
		$.fancybox.open(options);
		return false;
	});
	
	//=============== Date Pick =====================
	$('body').on('focus', '.datepicker', function(event) {
		$(this).datetimepicker({
			format: "DD-MM-YYYY",
			pickTime: false,
		});
	});
	$('body').on('click', '.input-group-append .input-group-text', function(){
		$(this).closest('.input-group-text').find('.form-control').trigger('focus');
	});
	
	$('.la-calendar-o').click(function() {
         $(this).parent().parent().siblings().trigger('focus');
    });


	
	$('body').on('focus', '.month', function(event) {
		$(this).datetimepicker({
			format: 'MM',
                 minViewMode: 'months',
                 maxViewMode: 'months',
                 startView: 'months',
                 pickTime: false,
                 viewMode: 'months',
		});
	});
	
	$('body').on('click', '.input-group.month .input-group-addon', function(){
		$(this).closest('.input-group.month').find('.form-control').trigger('focus');
	});
	
	//================== Time Pick ==================
	$('body').on('focus', '.input-group.time .form-control', function(event) {
		$(this).datetimepicker({
			// format: "H-i-s",
			pickDate: false,
		});
	});
	$('body').on('click', '.input-group.time .input-group-addon', function(){
		$(this).closest('.input-group.time').find('.form-control').trigger('focus');
	});
	
	//============= Formlair ===============
    $('input.form-control, textarea.form-control').blur(function(){
		formval = $(this).val();

		if(formval != '') {
			$(this).addClass('filled');
		} else {
			$(this).removeClass('filled');
		}
	});
	
	/* Mobile number only numberic validation */
    $('.col-sm-12').on('keydown', '#mobile', function(e) {
        -1 !== $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) || /65|67|86|88/.test(e.keyCode) && (!0 === e.ctrlKey || !0 === e.metaKey) || 35 <= e.keyCode && 40 >= e.keyCode || (e.shiftKey || 48 > e.keyCode || 57 < e.keyCode) && (96 > e.keyCode || 105 < e.keyCode) && e.preventDefault()
    });
    
    if ( $("select").hasClass("multiselect2") ) {
        $(".multiselect2").multiselect({
        	includeSelectAllOption: true,
        	enableFiltering: true,
        	buttonWidth: '100%',
        	maxHeight: 200,
        });  
    }
    
    /* Remove autocomplete from datepicker */
    $('.datepicker').attr("autocomplete", "off");
    
    //=============== Date Pick Sellout =====================
	function get_current_date(cond) {
	    var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
        
        if(dd<10) {
            dd = '0'+dd
        } 
        
        if(mm<10) {
            mm = '0'+mm
        } 
        
        today = dd + '/' + mm + '/' + yyyy;
        if(cond == 'start_date_cond') {
            return "01/"+mm+"/"+yyyy;
        } else if(cond == 'end_date_cond') {
            return "07/"+mm+"/"+yyyy;
        } else {
            return today;
        }
	}
	
	var dateFrom = get_current_date('start_date_cond');
    var dateTo = get_current_date('end_date_cond');
	var dateCheck = get_current_date('none');
	
	var d1 = dateFrom.split("/");
    var d2 = dateTo.split("/");
    var ch = dateCheck.split("/");
    
    var from = new Date(d1[2], parseInt(d1[1])-1, d1[0]);  // -1 because months are from 0 to 11
    var to   = new Date(d2[2], parseInt(d2[1])-1, d2[0]);
    var check = new Date(ch[2], parseInt(ch[1])-1, ch[0]);
	
    var currentTime = new Date();
    if(check >= from && check <= to) {
    //if(check > from && check < to) {
        var minDate = new Date(currentTime.getFullYear(), currentTime.getMonth(), -30); //one day next before month
    } else {
        var minDate = new Date(currentTime.getFullYear(), currentTime.getMonth(), +1);
    }
    var maxDate =  new Date(currentTime.getFullYear(), currentTime.getMonth() +1); // one day before next month
	$('body').on('focus', '.datepicker-sellout', function(event) {
		$(this).datetimepicker({
			format: "DD-MM-YYYY",
			pickTime: false,
			minDate: minDate,
			maxDate: new Date()
		});
	});

});