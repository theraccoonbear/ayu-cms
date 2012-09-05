$(document).ready(function() {
	// events
	var correctDays = function(ctxt) {
		var $month = $(ctxt);
		var $day = $(ctxt).parent().find('select[name*="day"]');
		var $year = $(ctxt).parent().find('select[name*="year"]');
		
		
		var month = parseInt($month.attr('value'));
		var day = parseInt($day.attr('value'));
		var year = parseInt($year.attr('value'));
		
		var d = (new Date(year, month, 0)).getUTCDate();
		
		
		$day.children().remove();
		
		for (var i = 1; i <= d; i++) {
			var opt = '<option value="' + (i < 10 ? "0" + i : i) + '"' + (day == i ? ' selected="selected"' : '') + '>' + i + '</option>';
			
			$day.append(opt);
		}
	}; // correctDays()
	
	
	var $month_picker = $('div.input.datetime select[name*="month"]');
	correctDays($month_picker);
	
	$('div.input.datetime select[name*="month"], div.input.datetime select[name*="year"]').change(function(e) {
		correctDays('div.input.datetime select[name*="month"]');
		e.preventDefault();
	});
	
	var $event_hour = $('#the_timeHour');
	var $event_min = $('#the_timeMinute');
	var $event_ampm = $('#the_timeMeridian');
	var $event_date = $('#the_date');
	var $actual_date = $('#actualDate');
	
	var update_date = function() {
		var sel_date = new Date($event_date.datepicker('getDate'));
		var month = sel_date.getMonth() + 1;
		var day = sel_date.getDate();
		var year = 1900 + sel_date.getYear();
		var hour = parseInt($event_hour.val()) + ($event_ampm.val() == 'am' ? 0 : 12);
		var mins = $event_min.val();
		
		hour = hour < 10 ? '0' + hour.toString() : hour;
		month = month < 10 ? '0' + month.toString() : month;
		day = day < 10 ? '0' + day.toString() : day;
		var new_date = year + '-' + month + '-' + day + ' ' + hour + ':' + mins + ':00';
		$actual_date.val(new_date);
	};
	
  $event_hour.change(function(e) {
		update_date();
	});
	
	$event_min.change(function(e) {
		update_date();
	});
	
	$event_ampm.change(function(e) {
		update_date();
	});
	
	$event_date.datepicker({
		onSelect: function(dateText, inst) {
			update_date();
		},
		dateFormat: 'MM d, yy'
	});
	

	
	// Assets
	$('#methodUploadFile').click(function(e) {
		$('.fileMethod').hide();
		
		$('#fileUploader').show();
		$('#fileChangeMethod').attr('value', 'upload');
		
		e.preventDefault()
	});
	
	$('#methodEditFile').click(function(e) {
		$('.fileMethod').hide();
		
		$('#fileEditor').show();
		$('#fileChangeMethod').attr('value', 'edit');
		
		e.preventDefault()
	});
	
	var $preview = $('#assetPreview');
	var $prevImg = $preview.find('img');
	var prev_hide_timeout = -1;
	
	var hidePreview = function(ctxt, e) {
		$preview.hide();
		$prevImg.attr('src', '/img/spinner.gif');
	};
	
	var showPreview = function(ctxt, e) {		
		clearTimeout(prev_hide_timeout);
			
		$preview.css({'left':e.pageX+50,'top':e.pageY-50}).show();
		
		var img = new Image();
		img.onload = function() {
			$prevImg.attr('src', $(img).attr('src'));
		};
		img.src = $(ctxt).attr('rel');
	};
	
	$('a.assetLink').hover(function(e) {
		if (e.type == 'mouseenter') {
			showPreview(this, e);
		} else {
			hidePreview(this, e);
		}
	});
	
	// Site Index
	
	$('.pageEntry table tr').hover(function(e) {
		$actions = $(this).find('td.pageActions > span');
		if (e.type == 'mouseenter') {
			$actions.show();
		} else {
			$actions.hide();
		}
	});
	
	// Submissions
	$('.filters select').change(function(e) {
		document.location = '/admin/forms/index/formFilter:' + $(this).val();
	});
	
	// general code editing (allow tab insertion)
	function insertAtCaret(area, text) {
		var txtarea = $(area).get(0);
		var scrollPos = txtarea.scrollTop;
		var strPos = 0;
		var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? "ff" : (document.selection ? "ie" : false ) );
		if (br == "ie") { 
			txtarea.focus();
			var range = document.selection.createRange();
			range.moveStart ('character', -txtarea.value.length);
			strPos = range.text.length;
		}
		else if (br == "ff") strPos = txtarea.selectionStart;

		var front = (txtarea.value).substring(0,strPos);  
		var back = (txtarea.value).substring(strPos,txtarea.value.length); 
		txtarea.value=front+text+back;
		strPos = strPos + text.length;
		if (br == "ie") { 
			txtarea.focus();
			var range = document.selection.createRange();
			range.moveStart ('character', -txtarea.value.length);
			range.moveStart ('character', strPos);
			range.moveEnd ('character', 0);
			range.select();
		}
		else if (br == "ff") {
			txtarea.selectionStart = strPos;
			txtarea.selectionEnd = strPos;
			txtarea.focus();
		}
		txtarea.scrollTop = scrollPos;
	}
	
	$('textarea.codeEdit').keydown(function(e) {
		var $ta = $(this);
		switch (e.keyCode) {
			case 9:
				insertAtCaret(this, "  ");
				e.preventDefault();
			default:
				break;
		}
	});
	
});