function get_date_diff(from,to){
	return 1+(to - from)/ 24 / 60 / 60 / 1000;	
}
var map;
geocoder = new google.maps.Geocoder();

  function codeAddress(element,address) {
    //In this case it gets the address from an element on the page, but obviously you  could just pass it to the method instead
    // var address = 'Barnard St, Pretoria 0153';

    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        //In this case it creates a marker, but you can get the lat and lng from the location.LatLng
        
		var mapOptions = {
			zoom: 10,
			center: new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng())
		  };
		
		map = new google.maps.Map(element,mapOptions);
		map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map, 
            position: results[0].geometry.location
        });
		
		
		  
		
      } else {
       // alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
jQuery(document).ready(
function ()
	{
	/* REcurring displays */
	
	jQuery('input[name="recurring"]').click(
		function()
			{
				if(jQuery(this).val()==1)
					jQuery('div.recurring_settings').slideDown('fast');
				else
					jQuery('div.recurring_settings').slideUp('fast');
			}
		);
	
	jQuery('select[name="repeat_event"]').change(
		function()
			{
			if(jQuery(this).val()=='daily')
				{
				jQuery('div.daily_settings').slideDown('fast');
				jQuery('div.weekly_settings').slideUp('fast');
				jQuery('div.montly_settings').slideUp('fast');
				}
			if(jQuery(this).val()=='weekly')
				{
				jQuery('div.daily_settings').slideUp('fast');
				jQuery('div.weekly_settings').slideDown('fast');
				jQuery('div.monthly_settings').slideUp('fast');
				}
			if(jQuery(this).val()=='monthly')
				{
				jQuery('div.daily_settings').slideUp('fast');
				jQuery('div.weekly_settings').slideUp('fast');
				jQuery('div.monthly_settings').slideDown('fast');
				}
			}
		);
	
	/************************/
	jQuery('input[name="event_color"]').ColorPicker({
		onChange: function (hsb, hex, rgb) {
		jQuery('input[name="event_color"]').css('background','#'+hex);
		jQuery('input[name="event_color"]').val('#'+hex);
		jQuery('input[name="event_color"]').trigger('change');
		}
	}
	);	
		
	jQuery('div.event_holder div.db_actions div.delete').live('click',
		function()
			{
			//alert('delete?');
			}
		);
	
	jQuery('.event_time_line, .event_info_holder').live('click',
		function()
			{
			
			 jQuery( "#event-info" ).dialog(
			 	{
				autoOpen: false,
				modal: true,
				draggable: false,
				resizable:false,
				dialogClass: "event_information",
				title: jQuery(this).text(),
				show: { effect: "fadeIn",duration: 300 },
				hide: { effect: "fadeOut",duration: 500 },
				}
			).html('<div class="load_spinner"></div> Loading event information...');

			var data = 
				{
				action	 	: 'get_event_info',
				event_Id	: jQuery(this).attr('id'),
				page		: jQuery('.page').text()
				};
			//jQuery( "#event-info" ).html('<div class="load_spinner"></div>');		
			jQuery.post
				(
				ajaxurl, data, function(response)
					{
					jQuery( "#event-info" ).html(response);
					//var set_map = ;
					codeAddress(document.getElementById('venue_map'),jQuery( "#event-info" ).find('.map_address').text());
					}
				);
					
				
		
			
			jQuery( "#event-info" ).dialog( "open" );
			}
		);
	jQuery( ".add_event_editor" ).dialog(
		{
		autoOpen: false,
		show: { effect: "fadeIn",duration: 500 },
		hide: { effect: "fadeOut",duration: 500 },
		modal: true,
		draggable: false,
		resizable:false,
		title: 'Add New Event',
		dialogClass: "event_editor"
		}
	);
	var currentTime = new Date()
	var month = currentTime.getMonth() + 1
	var day = currentTime.getDate()
	var year = currentTime.getFullYear()		
	jQuery('input.repeat_until').datepicker(
		{ 
		yearRange: year +":" + (year+1) 
		}
	);
	jQuery('input.datepicker').datepicker();
	jQuery('input.timepicker').timepicker();				
	jQuery('input.multi-dates').multiDatesPicker();
	//jQuery('select[name="exclude_days"]').multiselect();
	jQuery('select[name="exclude_days[]"]').multiSelect(
		{
		selectableHeader: "<div class='custom-header'>Select</div>",
  		selectionHeader: "<div class='custom-header'>Excluded days</div>",
		}
	);		
	jQuery('select[name="include_days[]"]').multiSelect(
		{
		selectableHeader: "<div class='custom-header'>Select</div>",
  		selectionHeader: "<div class='custom-header'>Selection</div>",
		}
	);	
	jQuery('select[name="monthly_days[]"]').multiSelect(
		{
		selectableHeader: "<div class='custom-header'>Select</div>",
  		selectionHeader: "<div class='custom-header'>Selection</div>",
		}
	);	
	jQuery('select[name="monthly_conditions_num[]"]').multiSelect(
		{
		selectableHeader: "<div class='custom-header'>Select</div>",
  		selectionHeader: "<div class='custom-header'>Selection</div>",
		}
	);	
	jQuery('select[name="monthly_conditions_days[]"]').multiSelect(
		{
		selectableHeader: "<div class='custom-header'>Select</div>",
  		selectionHeader: "<div class='custom-header'>Selection</div>",
		}
	);	
	/******************************************************/
	/**********************ADD EVENT **********************/
	/******************************************************/			
	jQuery('.add_event').click(
		function()
			{
				
			
			
			
			var description 			= jQuery('#xcalendar_event_description_ifr').contents().find('body').html();
			var title 					= jQuery('div#titlewrap input#title').val();
			var start_date 				= jQuery('input[name="start_date"]').val();//jQuery(this).attr('data-day') + '-' + jQuery(this).attr('data-month') + '-' + jQuery(this).attr('data-year');
			var end_date 				= jQuery('input[name="start_date"]').val();
			var start_time 				= jQuery('input[name="start_time"]').val();
			var end_time 				= jQuery('input[name="end_time"]').val();
			var venue 					= jQuery('input[name="venue"]').val();
			var recurring 				= '0';
			var xforms_Id	 			= jQuery('select[name="xforms_Id"]').val();
			
			//alert(recurring);
			
			
			
			//			= jQuery('select[name="exclude_days[]"]').val();
			var sort_date				= start_date.substring(6) + '-' + start_date.substring(0, 2) + '-' + start_date.substring(3, 5) + ' '+((start_time) ? start_time : '00:00' ) + ':00';
			//var exclude_days			= jQuery('select[name="exclude_days"]').multiSelect("getChecked").map(function(){ return this.value; }).get();
			var event_info = '';
			//alert(exclude_days);
			var get_s_date = new Date(parseInt(start_date.substring(6), 10),parseInt(start_date.substring(0, 2), 10) - 1, parseInt(start_date.substring(3, 5), 10));
			var get_e_date = new Date(parseInt(end_date.substring(6), 10),parseInt(end_date.substring(0, 2), 10) - 1, parseInt(end_date.substring(3, 5), 10));
				
		
			if(jQuery('.editing_Id').text()!='')
				{
				jQuery('.event_'+jQuery('.editing_Id').text()).remove();
				jQuery( "#event-info" ).dialog( "close" );
				}
			var data =
				{
				action	 					: (jQuery('.editing_Id').text()!='') ? 'edit_event' : 'insert_event',
				table						: 'wap_x_calendar',
				edit_Id						: jQuery('.editing_Id').text(),
				plugin						: 'shared',
				title						: title,
				start_date					: start_date,
				end_date					: end_date,
				start_time					: start_time,
				end_time					: end_time,
				venue						: venue,
				description					: description,
				recurring					: '0',
				sort_date					: sort_date,
				xforms_Id					: xforms_Id
				};		
			jQuery.post(ajaxurl, data, function(response){ 
			location.reload();
			//jQuery('.editing_Id').text(''); jQuery('.add_event').val('Add Event'); 
			//events.push(["",parseInt(start_date.substring(0, 2), 10),parseInt(start_date.substring(3, 5), 10),parseInt(start_date.substring(6), 10),title,"",start_date,end_date,start_time,end_time,"","","",Math.random(9999999),"http://localhost/wp3.6",event_color,response]);
			//createCalendar();
			//resize_blocks();
			//jQuery( ".add_event_editor" ).dialog( "close" );
			});

			}
		);
	jQuery('div.tr.month_days div.td').live('hover',
		function()
			{
			jQuery('div.add_new_event').hide();
			jQuery(this).addClass('over');
			jQuery(this).find('div.add_new_event').show();
			}
		);
	jQuery('div.tr.month_days div.td').live('mouseleave',
		function()
			{
			jQuery(this).removeClass('over');
			jQuery('div.add_new_event').hide();
			}
		);
		
	
	jQuery('div.update div.delete').live('click',
		function()
			{
			if(confirm('Are your sure you want to permanently delete this event?'))
				{
				var data =
					{
					action	 		: 'delete_event',
					table			: 'wap_x_calendar',
					Id				: jQuery(this).attr('id')
					};		
				jQuery.post(ajaxurl, data, function(response){
					location.reload();
				});
				}
			}
		);
	jQuery('div.update div.edit').live('click',
		function()
			{
			var event_info = jQuery(this).parent().parent().parent();
			jQuery('#xcalendar_event_description_ifr').contents().find('body').html(event_info.find('.event_description').html());
			jQuery('div#titlewrap input#title').val(event_info.find('.ui-dialog-title').html());
			jQuery('input[name="event_color"]').val(event_info.find('.db_details .event_color').html());
			jQuery('input[name="event_color"]').css('background',event_info.find('.db_details .event_color').html());
			jQuery('input[name="start_date"]').val(event_info.find('.db_details .event_start_date').html());
			jQuery('input[name="end_date"]').val(event_info.find('.db_details .event_end_date').html());
			jQuery('input[name="start_time"]').val(event_info.find('.db_details .event_start_time').html());
			jQuery('input[name="end_time"]').val(event_info.find('.db_details .event_end_time').html());
			jQuery('input[name="venue"]').val(event_info.find('.venue_name').html());
			jQuery('input[name="google_map"]').val(event_info.find('.db_details .google_map').html());
			jQuery('input[name="repeat_until"]').val(event_info.find('.db_details .repeat_until').html());
			jQuery('input[name="exclude_dates"]').val(event_info.find('.db_details .exclude_dates').html());
			jQuery('input[name="google_map"]').val(event_info.find('.db_details .google_map').html());
			jQuery('select[name="xforms_Id"] option[value='+ event_info.find('.db_details .xforms_id').html() +']').attr('selected',true);
			
			
			var multi_selections = new Array('exclude_days','include_days','monthly_days','monthly_conditions_num','monthly_conditions_days');
			var multi_sel_arry = '';
			
				for(var i=0;i < multi_selections.length; i++)
					{
					if(event_info.find('.db_details .'+ multi_selections[i]).html()!='')
						{
						//multi_sel_arry = jQuery.parseJSON(event_info.find('.db_details .'+ multi_selections[i]).html());
						jQuery.map(multi_sel_arry, function(val, key) {
							 if(val!='')
								{
								jQuery('select[name="'+ multi_selections[i] +'[]"]').parent().find('.ms-selectable').find('ul.ms-list li').each(
									function(index)
										{
										if(jQuery(this).find('span').text()==val)
											jQuery(this).hide().removeClass('ms-selected');
										}
									);
								jQuery('select[name="'+ multi_selections[i] +'[]"]').parent().find('.ms-selection').find('ul.ms-list li').each(
									function(index)
										{
										if(jQuery(this).find('span').text()==val)
											jQuery(this).show().addClass('ms-selected');
										}
									);
								}
							});
						}
					}
				
			
			
			
			
			
			
			//jQuery('input[name="recurring"]').val();
			jQuery('input[name="recurring"]').filter('[value='+event_info.find('.db_details .recurring').html()+']').prop('checked', true);
			
			if(event_info.find('.db_details .recurring').html()==1)
					jQuery('div.recurring_settings').slideDown('fast');
				else
					jQuery('div.recurring_settings').slideUp('fast');
			
			jQuery('select[name="repeat_event"] option[value='+ event_info.find('.db_details .repeat_event').html() +']').attr('selected',true);
			
			jQuery('select[name="repeat_event"]').trigger('change');
	
			
			jQuery('.editing_Id').text(jQuery(this).attr('id')); 
			jQuery('.add_event').val('Save');
			
			jQuery( ".add_event_editor" ).dialog("open");
			}
		);
	jQuery('.day_num').live('click',
		function()
			{
			//reset form values
			jQuery('.ms-selectable ul.ms-list li').show();
			jQuery('.ms-selection ul.ms-list li').hide().removeClass('ms-selected');
			jQuery('input[name="recurring"]').filter('[value=0]').prop('checked', true);
			jQuery('.recurring_settings').hide();
			jQuery('#xcalendar_event_description_ifr').contents().find('body').html('');
			jQuery('div#titlewrap input#title').val('');
			jQuery('input[name="event_color"]').val('');
			jQuery('input[name="event_color"]').css('background','');
			jQuery('input[name="start_date"]').val('');
			jQuery('input[name="end_date"]').val('');
			jQuery('input[name="start_time"]').val('');
			jQuery('input[name="end_time"]').val('');
			jQuery('input[name="venue"]').val('');
			jQuery('input[name="google_map"]').val('');
			jQuery('input[name="repeat_until"]').val('');
			jQuery('input[name="exclude_dates"]').val('');
			jQuery('input[name="google_map"]').val('');
			jQuery('select[name="xforms_Id"] option[value=0]').attr('selected',true);
			
			jQuery('select[name="repeat_event"] option[value=daily]').attr('selected',true);
			jQuery('select[name="repeat_event"]').trigger('change');
			
			jQuery('.day_num').removeClass('current');
			jQuery(this).addClass('current');
			
			var day 	= (jQuery(this).attr('data-day')<10) ? '0'+jQuery(this).attr('data-day') : jQuery(this).attr('data-day'); 
			var month 	= (jQuery(this).attr('data-month')<10) ? '0'+jQuery(this).attr('data-month') : jQuery(this).attr('data-month');
			
			jQuery('input[name="start_date"]').val(month + '/' + day + '/' + jQuery(this).attr('data-year'));
			jQuery('input[name="end_date"]').val(month + '/' + day + '/' + jQuery(this).attr('data-year'));

			
			jQuery( ".add_event_editor" ).dialog("open");
			}
		);
	
	jQuery('div.day_view_header span.ui-icon').live('click',
		function()
			{
			
			jQuery('div.day_view').slideUp('slow',function()
				{
				jQuery('div#calendar').slideDown('slow');
				});
			}
		);
		
	jQuery('span.total_events').live('click',
		function()
			{
			jQuery('div.day_view').html('Loading...');
			jQuery('div#calendar').slideUp('slow',function()
				{
				jQuery('div.day_view').slideDown('slow');
				});
			
			
			var data =
					{
					action	 		: 'day_view',
					table			: 'wap_x_calendar',
					the_date		: jQuery(this).parent().attr('data-date'),
					event_ids		: jQuery(this).parent().attr('data-event-ids')
					};		
				jQuery.post(ajaxurl, data, function(response){
					
					jQuery('div.day_view').html(response);
					
				});
			}
		);
	
	
	jQuery('input[name="update_settings"]').click(
		function()
			{
			var current_button_text = jQuery(this).val();
			jQuery(this).val('   Saving...   ')
			var data =
					{
					action	 		: 'update_settings',
					
					envato_username	: jQuery('input[name="envato_username"]').val(),
					promo_text		: jQuery('input[name="promo_text"]').val(),
					
					};		
				jQuery.post(ajaxurl, data, function(response){
					jQuery('input[name="update_settings"]').val('   Settings Saved   ');
					setTimeout(function(){ jQuery('input[name="update_settings"]').val(current_button_text) },2000);
				});
			}
		);
	
	
		
	/* ADD Hover effect*/
	jQuery('div.event_time_line').live('hover',
		function()
			{
			jQuery('.event_'+jQuery(this).attr('id')).addClass('ui-state-hover');
			}
		);
	jQuery('div.event_time_line').live('mouseleave',
		function()
			{
			jQuery('.event_'+jQuery(this).attr('id')).removeClass('ui-state-hover');
			}
		);
	
	jQuery('span.total_events').live('hover',
		function()
			{
			jQuery(jQuery(this)).removeClass('ui-state-active');
			jQuery(this).addClass('ui-state-hover');
			}
		);
	jQuery('span.total_events').live('mouseleave',
		function()
			{
			jQuery(jQuery(this)).removeClass('ui-state-hover');
			jQuery(this).addClass('ui-state-active');
			}
		);
		
	jQuery('.time_holder').live('hover',
		function()
			{
			jQuery(this).addClass('ui-state-hover');
			}
		);
	jQuery('.time_holder').live('mouseleave',
		function()
			{
			jQuery(jQuery(this)).removeClass('ui-state-hover');
			}
		);
	
	jQuery('div.db_actions').live('hover',
		function()
			{
			jQuery(this).parent().addClass('over');
			}
		);
	jQuery('div.db_actions').live('mouseleave',
		function()
			{
			jQuery(this).parent().removeClass('over');
			}
		);
		
		
		
	jQuery('select[name="change_color_scheme"]').change(
		function()
			{
			jQuery('#xcalender').find('link.color_scheme').attr('href',jQuery('#site_url').text() + '/wp-content/plugins/X%20Calendar/css/ui-themes/'+ jQuery(this).val() +'/jquery.ui.theme.css');	
			}
		);
	jQuery('select[name="change_day_display"]').change(
		function()
			{
			var day_headings ='';
			switch(jQuery(this).val())
				{
				case 'full':
					wordDays = new Array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
				break;
				case 'abr':
					wordDays = new Array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
				break;
				case 'fl':
					wordDays = new Array("M","T","W","T","F","S","S");
				break;
				default:
					wordDays = new Array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
				break;	
				}
			createCalendar();
			resize_blocks();
			}
		);
		
		
		jQuery('select[name="change_month_display"]').change(
		function()
			{
			var day_headings ='';
			switch(jQuery(this).val())
				{
				case 'full':
					wordMonth = new Array("January","February","March","April","May","June","July","August","September","October","November","December");
				break;
				case 'abr':
					wordMonth = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
				break;
				default:
					wordMonth = new Array("January","February","March","April","May","June","July","August","September","October","November","December");
				break;	
				}
			createCalendar();
			resize_blocks();
			}
		);
		
		
		
			
		
		
		
	jQuery(window).resize(function()
		{
		 resize_blocks();
		}
	);
	resize_blocks();
	}
);

function resize_blocks(){
	
	var get_day_block_width = jQuery('div.tr.day_headings div.td').outerWidth();
	jQuery('div.tr.month_days div.td').attr('style','height: '+(get_day_block_width) +'px');
	jQuery('div.tr.month_days').each
		(
		function()
			{
			var td_heigth;
			var set_height = 0;
			jQuery(this).find('div.td').each(
			function()
				{		
				td_heigth = 0;
				jQuery(this).find('div.event_time_line').each(
				function(index)
					{
					td_heigth += 70;
					}
				);
				if((td_heigth+jQuery(this).find('.day_num').outerHeight())>(get_day_block_width))
					{
					if(td_heigth>set_height)
						{
						set_height = td_heigth;
						jQuery(this).parent().find('.td').attr('style','height: ' + td_heigth + 'px');
						}
					}
				});
			}
		);
}



function changedate(buttonpressed) {
	if (buttonpressed == "prevyr") yearNum--;
	else if (buttonpressed == "nextyr") yearNum++;
	else if (buttonpressed == "prevmo") monthNum--;
	else if (buttonpressed == "nextmo") monthNum++;
	else if (buttonpressed == "return") { 
		monthNum = todaysMonth;
		yearNum = todaysYear;
	}
	if (monthNum == 0) {
		monthNum = 12;
		yearNum--;
	}
	else if (monthNum == 13) {
		monthNum = 1;
		yearNum++
	}
	lastDate = new Date(String(monthNum+1)+"/0/"+String(yearNum));
	numbDays = daysInMonth(monthNum-1,yearNum);
	firstDate = new Date(String(monthNum)+"/1/"+String(yearNum));
	firstDay = firstDate.getDay() + 1;
	
	jQuery('.loading').css('width',jQuery('div.table').outerWidth());
	jQuery('.loading').css('height',jQuery('div.table').outerHeight());
	jQuery('.loading').css('line-height',jQuery('div.table').outerHeight());
	
	jQuery('.loading .load_spinner').css('width',jQuery('div.table').outerWidth());
	jQuery('.loading .load_spinner').css('height',jQuery('div.table').outerHeight());
	
	jQuery('.loading').show();
	
	setTimeout(function(){ createCalendar();resize_blocks();},500);
	
	return;
}
function daysInMonth(iMonth, iYear)
	{
	return 32 - new Date(iYear, iMonth, 32).getDate();
	}
function easter(year) {
// feed in the year it returns the month and day of Easter using two GLOBAL variables: eastermonth and easterday
    var a = year % 19;
    var b = Math.floor(year/100);
    var c = year % 100;
    var d = Math.floor(b/4);
    var e = b % 4;
    var f = Math.floor((b+8) / 25);
    var g = Math.floor((b-f+1) / 3);
    var h = (19*a + b - d - g + 15) % 30;
    var i = Math.floor(c/4);
    var j = c % 4;
    var k = (32 + 2*e + 2*i - h - j) % 7;
    var m = Math.floor((a + 11*h + 22*k) / 451);
    var month = Math.floor((h + k - 7*m + 114) / 31);
    var day = ((h + k - 7*m +114) % 31) + 1;
    eastermonth = month;
    easterday = day;
}

function createCalendar() {
	var calendarString = '';
	var daycounter = 0;
	//calendarString += '<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">';
	if(is_sidebar)
		{
		wordDays = new Array("M","T","W","T","F","S","S");
		wordMonth = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		
		calendarString += '<div class="table" >';
			//calendarString += '<td colspan="7">';
				//calendarString += '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
					calendarString += '<div class="tr head ">';
						calendarString += '<div class="td ui-widget-header left_arrow" style="border-right:1px solid #ccc;border-left:1px solid #ccc;"><a href="javascript:changedate(\'prevyr\');"><span class="ui-icon ui-icon-seek-prev"></span></a></div>';
						calendarString += '<div class="td ui-widget-header left_arrow"><a href="javascript:changedate(\'prevmo\');"><span class="ui-icon ui-icon-circle-triangle-w"></span></a></div>';
						calendarString += '<div class="td ui-widget-header" style="padding-right:1px;padding-left:1px;">&nbsp;</div>';
						calendarString += '<div class="td ui-widget-header current_month"><span>' + wordMonth[monthNum-1] + ' ' + yearNum + '</span></div>';
						calendarString += '<div class="td ui-widget-header" style="padding-right:1px;padding-left:1px;">&nbsp;</div>';
						calendarString += '<div class="td ui-widget-header right_arrow" style=""><a href="javascript:changedate(\'nextmo\');"><span class="ui-icon ui-icon-circle-triangle-e"></span></a></div>';
						calendarString += '<div class="td ui-widget-header right_arrow" style="border-left:1px solid #ccc;border-right:1px solid #ccc;"><a href="javascript:changedate(\'nextyr\');"><span class="ui-icon ui-icon-seek-next"></span></a></div>';
					calendarString += '</div>';
				//calendarString += '</div>';
				
				
			//calendarString += '</td>';
			//calendarString += '</tr>';
			calendarString += '<div  class="tr day_headings">';
				calendarString += '<div class="td ui-state-default"  >'+ wordDays[6] +'</div>';
				calendarString += '<div class="td ui-state-default"  >'+ wordDays[0] +'</div>';
				calendarString += '<div class="td ui-state-default"  >'+ wordDays[1] +'</div>';
				calendarString += '<div class="td ui-state-default"  >'+ wordDays[2] +'</div>';
				calendarString += '<div class="td ui-state-default"  >'+ wordDays[3] +'</div>';
				calendarString += '<div class="td ui-state-default"  >'+ wordDays[4] +'</div>';
				calendarString += '<div class="td ui-state-default"  >'+ wordDays[5] +'</div>';
			calendarString += '</div>';	
		
		thisDate = 1;
		var set_rows = 5;
	if(firstDay>=6 && numbDays>30)
		 set_rows = 6;
	for (var i = 1; i <= set_rows; i++)
		{
		calendarString += '<div class="tr month_days" >';
		for (var x = 1; x <= 7; x++)
			{
			daycounter = (thisDate - firstDay)+1;
			thisDate++;
			var set_day = (daycounter<10) ? '&nbsp;'+daycounter+'&nbsp;' : daycounter;
			if ((daycounter > numbDays) || (daycounter < 1)) 
				{
				calendarString += '<div align="center"  class="td ui-widget-overlay">&nbsp;</div>';
				} 
			else 
				{
				var monthNum_leading_zero = monthNum;
				var daycounter_leading_zero = daycounter;
				
				if(monthNum<10)
					monthNum_leading_zero = '0'+monthNum;
				if(daycounter<10)
					daycounter_leading_zero = '0'+daycounter;
				var count_events = 0;
				var event_ids = '';	
				for (var k = 0; k < events.length; k++)
								{
								//alert(monthNum_leading_zero + events[k][6] + '####' + monthNum +'/'+ daycounter +'/'+ yearNum);
								if(events[k][6]==(monthNum_leading_zero +'/'+ daycounter_leading_zero +'/'+ yearNum ))
									{
									count_events++;
									event_ids += events[k][16] +',';
									}
								}
				if (checkevents(daycounter,monthNum,yearNum,i,x) || ((todaysDay == x) && (todaysDate == daycounter) && (todaysMonth == monthNum)))
					{
					
					if ((todaysDay == x) && (todaysDate == daycounter) && (todaysMonth == monthNum))
						{
						
						calendarString += '<div id="td-day-'+daycounter+'" align="center"  class="today td ui-state-default" data-month="'+ monthNum +'" data-year="'+ yearNum +'" data-day="'+ daycounter +'" data-event-ids="'+ event_ids +'" data-date="'+ monthNum_leading_zero +'/'+ daycounter_leading_zero +'/'+ yearNum +'"><div class="day_number">'+ set_day +'</div>'+ ((count_events!=0) ? '<span class="total_events ui-state-active">'+count_events +'</span>': '')+'</div>';
						
						}
 					else
						{
						
						
						calendarString += '<div id="td-day-'+daycounter+'" align="center"  class="hasevent td ui-state-default" data-month="'+ monthNum +'" data-year="'+ yearNum +'" data-day="'+ daycounter +'" data-event-ids="'+ event_ids +'" data-date="'+ monthNum_leading_zero +'/'+ daycounter_leading_zero +'/'+ yearNum +'"><div class="day_number">'+ set_day +'</div>'+ ((count_events!=0) ? '<span class="total_events ui-state-active">'+count_events +'</span>': '')+'</div>';	
						}
					} 
				else
					{
					calendarString += '<div id="td-day-'+daycounter+'" align="center" class="td in_scope ui-state-default" data-event-ids="'+ event_ids +'" data-date="'+ monthNum_leading_zero +'/'+ daycounter_leading_zero +'/'+ yearNum +'"><div class="day_number">'+ set_day +'</div>'+ ((count_events!=0) ? '<span class="total_events ui-state-active">'+count_events +'</span>': '')+'</div>';
					}
				}
			}
		}
		calendarString += '</div>';
		calendarString += ((envato_username!='') ? '<div class="promote"><a href="http://codecanyon.net/user/Basix/portfolio?ref='+ envato_username +'">'+ ((promo_text) ? promo_text : 'Powered by X-Calendar') +'</a></div>' : '');
		jQuery('div#calendar').html(calendarString);
		}
	else
		{
	calendarString += '<div class="loading"><div class="load_spinner"></div>Loading...</div>';
	
		calendarString += '<div class="table" >';
			//calendarString += '<td colspan="7">';
				//calendarString += '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
					calendarString += '<div class="tr head ">';
						calendarString += '<div class="td ui-widget-header left_arrow" style="border-right:1px solid #ccc;border-left:1px solid #ccc;"><a href="javascript:changedate(\'prevyr\');"><span class="ui-icon ui-icon-seek-prev"></span></a></div>';
						calendarString += '<div class="td ui-widget-header left_arrow"><a href="javascript:changedate(\'prevmo\');"><span class="ui-icon ui-icon-circle-triangle-w"></span></a></div>';
						calendarString += '<div class="td ui-widget-header" style="padding-right:1px;padding-left:1px;">&nbsp;</div>';
						calendarString += '<div class="td ui-widget-header current_month">' + wordMonth[monthNum-1] + '&nbsp;&nbsp;' + yearNum + '</div>';
						calendarString += '<div class="td ui-widget-header" style="padding-right:1px;padding-left:1px;">&nbsp;</div>';
						calendarString += '<div class="td ui-widget-header right_arrow" style=""><a href="javascript:changedate(\'nextmo\');"><span class="ui-icon ui-icon-circle-triangle-e"></span></a></div>';
						calendarString += '<div class="td ui-widget-header right_arrow" style="border-left:1px solid #ccc;border-right:1px solid #ccc;"><a href="javascript:changedate(\'nextyr\');"><span class="ui-icon ui-icon-seek-next"></span></a></div>';
					calendarString += '</div>';
				//calendarString += '</div>';
				
				
			//calendarString += '</td>';
		//calendarString += '</tr>';
		calendarString += '<div  class="tr day_headings">';
			calendarString += '<div class="td ui-state-default"  >'+ wordDays[6] +'</div>';
			calendarString += '<div class="td ui-state-default"  >'+ wordDays[0] +'</div>';
			calendarString += '<div class="td ui-state-default"  >'+ wordDays[1] +'</div>';
			calendarString += '<div class="td ui-state-default"  >'+ wordDays[2] +'</div>';
			calendarString += '<div class="td ui-state-default"  >'+ wordDays[3] +'</div>';
			calendarString += '<div class="td ui-state-default"  >'+ wordDays[4] +'</div>';
			calendarString += '<div class="td ui-state-default"  >'+ wordDays[5] +'</div>';
		calendarString += '</div>';
	thisDate = 1;
	
	var repeat_event = new Array();
	var month_over = new Array();
	//alert(firstDay);
	//alert(numbDays);
	var set_rows = 5;
	if(firstDay>=6 && numbDays>30)
		 set_rows = 6;
	for (var i = 1; i <= set_rows; i++)
		{
		calendarString += '<div class="tr month_days" >';
		for (var x = 1; x <= 7; x++)
			{
			daycounter = (thisDate - firstDay)+1;
			thisDate++;
			var set_day = (daycounter<10) ? '&nbsp;'+daycounter+'&nbsp;' : daycounter;
			if ((daycounter > numbDays) || (daycounter < 1)) 
				{
				calendarString += '<div align="center"  class="td ui-widget-overlay">&nbsp;</div>';
				} 
			else 
				{
				
				var monthNum_leading_zero = monthNum;
				var daycounter_leading_zero = daycounter;
				
				if(monthNum<10)
					monthNum_leading_zero = '0'+monthNum;
				if(daycounter<10)
					daycounter_leading_zero = '0'+daycounter;
				var count_events = 0;
				var event_ids = '';	
				for (var k = 0; k < events.length; k++)
								{
								//alert(monthNum_leading_zero + events[k][6] + '####' + monthNum +'/'+ daycounter +'/'+ yearNum);
								if(events[k][6]==(monthNum_leading_zero +'/'+ daycounter_leading_zero +'/'+ yearNum ))
									{
									count_events++;
									event_ids += events[k][16] +',';
									}
								}	
				
				if (checkevents(daycounter,monthNum,yearNum,i,x) || ((todaysDay == x) && (todaysDate == daycounter) && (todaysMonth == monthNum)))
					{
					
					if ((todaysDay == x) && (todaysDate == daycounter) && (todaysMonth == monthNum))
						{
						calendarString += '<div id="td-day-'+daycounter+'" align="center"  class="today td ui-widget-content" data-month="'+ monthNum +'" data-year="'+ yearNum +'" data-day="'+ daycounter +'"><div data-event-ids="'+ event_ids +'" data-date="'+ monthNum_leading_zero +'/'+ daycounter_leading_zero +'/'+ yearNum +'" class="day_num ui-state-active" title="Today" data-month="'+ monthNum +'" data-year="'+ yearNum +'" data-day="'+ daycounter +'">'+ set_day +'</div>';
					for (var k = 0; k < events.length; k++)
								{
								var get_s_date = new Date(parseInt(events[k][6].substring(6), 10),parseInt(events[k][6].substring(0, 2), 10) - 1, parseInt(events[k][6].substring(3, 5), 10));
								var get_e_date = new Date(parseInt(events[k][7].substring(6), 10),parseInt(events[k][7].substring(0, 2), 10) - 1, parseInt(events[k][7].substring(3, 5), 10));
								if(daycounter==parseInt(events[k][6].substring(3, 5), 10) && monthNum==parseInt(events[k][6].substring(0, 2), 10) &&  yearNum==parseInt(events[k][6].substring(6), 10))
									{
									//calendarString += '<div class="event_holder" ><div class="ui-state-hover ui-corner-all event_title" title="'+events[k][4]+'">' + ((events[k][8]!='') ? events[k][8]+'-' : '') + events[k][4] + '<div class="event_info" style="display:none;"><div class="event_title" style="display:none;">' + events[k][4] + '</div><div class="event_venue">' + events[k][5] + '</div><div class="event_date_from">' + events[k][6] + '</div><div class="event_date_to">' + events[k][7] + '</div><div class="event_time_from">' + events[k][8] + '</div><div class="event_time_to">' + events[k][9] + '</div><div class="event_description">' + events[k][10] + '</div><div class="event_directions">' + events[k][11] + '</div><div class="event_map">' + events[k][11] + '</div></div></div><div class="db_actions"><div class="edit" data-event-id="' + events[k][16] + '"></div><div class="delete" data-event-id="' + events[k][16] + '"></div></div></div>';
									repeat_event[events[k][13]] = get_date_diff(get_s_date,get_e_date) + '--' + events[k][4] + '::' + daycounter + '__'+ events[k][16] + '**' + events[k][15] ;
									}
								
								}
						calendarString += '</div>';
						
						}
 					else
						{
						calendarString += '<div id="td-day-'+daycounter+'" align="center"  class="hasevent td ui-widget-content" data-month="'+ monthNum +'" data-year="'+ yearNum +'" data-day="'+ daycounter +'"><div data-event-ids="'+ event_ids +'" data-date="'+ monthNum_leading_zero +'/'+ daycounter_leading_zero +'/'+ yearNum +'" class="day_num ui-state-default" title="'+ checkevents(daycounter,monthNum,yearNum,i,x) +' events" data-month="'+ monthNum +'" data-year="'+ yearNum +'" data-day="'+ daycounter +'"><strong>'+ set_day +'</strong></div>';
					
							for (var k = 0; k < events.length; k++)
								{
								var get_s_date = new Date(parseInt(events[k][6].substring(6), 10),parseInt(events[k][6].substring(0, 2), 10) - 1, parseInt(events[k][6].substring(3, 5), 10));
								var get_e_date = new Date(parseInt(events[k][7].substring(6), 10),parseInt(events[k][7].substring(0, 2), 10) - 1, parseInt(events[k][7].substring(3, 5), 10));
								if(daycounter==parseInt(events[k][6].substring(3, 5), 10) && monthNum==parseInt(events[k][6].substring(0, 2), 10) &&  yearNum==parseInt(events[k][6].substring(6), 10))
									{
									//calendarString += '<div class="event_holder" ><div class="ui-state-hover ui-corner-all event_title" title="'+events[k][4]+'">' + ((events[k][8]!='') ? events[k][8]+'-' : '') + events[k][4] + '<div class="event_info" style="display:none;"><div class="event_title" style="display:none;">' + events[k][4] + '</div><div class="event_venue">' + events[k][5] + '</div><div class="event_date_from">' + events[k][6] + '</div><div class="event_date_to">' + events[k][7] + '</div><div class="event_time_from">' + events[k][8] + '</div><div class="event_time_to">' + events[k][9] + '</div><div class="event_description">' + events[k][10] + '</div><div class="event_directions">' + events[k][11] + '</div><div class="event_map">' + events[k][11] + '</div></div></div><div class="db_actions"><div class="edit" data-event-id="' + events[k][16] + '"></div><div class="delete" data-event-id="' + events[k][16] + '"></div></div></div>';
									repeat_event[events[k][13]] = get_date_diff(get_s_date,get_e_date) + '--' + events[k][4] + '::' + daycounter + '__'+ events[k][16] + '**' + events[k][15] ;
									}
								
								}
						calendarString += '</div>';
						}
					} 
				else
					{
					
					for (var k = 0; k < events.length; k++)
						{
						var get_s_date = new Date(parseInt(events[k][6].substring(6), 10),parseInt(events[k][6].substring(0, 2), 10) - 1, parseInt(events[k][6].substring(3, 5), 10));
						var get_e_date = new Date(parseInt(events[k][7].substring(6), 10),parseInt(events[k][7].substring(0, 2), 10) - 1, parseInt(events[k][7].substring(3, 5), 10));
						
						//alert(events[k][4]);
						
						if(daycounter==parseInt(events[k][7].substring(3, 5), 10) && monthNum==parseInt(events[k][7].substring(0, 2), 10) &&  yearNum==parseInt(events[k][7].substring(6), 10) && events[k][7].substring(0, 2) != events[k][6].substring(0, 2))
							{
							//calendarString += '<div class="event_holder" ><div class="ui-state-hover ui-corner-all event_title" title="'+events[k][4]+'">' + ((events[k][8]!='') ? events[k][8]+'-' : '') + events[k][4] + '<div class="event_info" style="display:none;"><div class="event_title" style="display:none;">' + events[k][4] + '</div><div class="event_venue">' + events[k][5] + '</div><div class="event_date_from">' + events[k][6] + '</div><div class="event_date_to">' + events[k][7] + '</div><div class="event_time_from">' + events[k][8] + '</div><div class="event_time_to">' + events[k][9] + '</div><div class="event_description">' + events[k][10] + '</div><div class="event_directions">' + events[k][11] + '</div><div class="event_map">' + events[k][11] + '</div></div></div><div class="db_actions"><div class="edit" data-event-id="' + events[k][16] + '"></div><div class="delete" data-event-id="' + events[k][16] + '"></div></div></div>';
							month_over[events[k][13]] = get_date_diff(get_s_date,get_e_date) + '--' + events[k][4] + '::' + daycounter + '__'+ events[k][16] + '**' + events[k][15] ;
							//alert('month_over');
							}
						
						if(monthNum<parseInt(events[k][7].substring(0, 2), 10) && monthNum>parseInt(events[k][6].substring(0, 2), 10))
							{
							month_over[events[k][13]] = get_date_diff(get_s_date,get_e_date) + '--' + events[k][4] + '::' + daycounter + '__'+ events[k][16] + '**' + events[k][15] ;
							}
						
						else if(yearNum<parseInt(events[k][7].substring(6), 10) && yearNum>parseInt(events[k][7].substring(6), 10))
							{
							month_over[events[k][13]] = get_date_diff(get_s_date,get_e_date) + '--' + events[k][4] + '::' + daycounter + '__'+ events[k][16] + '**' + events[k][15] ;
							}
						
						else if(monthNum<parseInt(events[k][7].substring(0, 2), 10) && monthNum!=parseInt(events[k][7].substring(0, 2), 10) && yearNum==parseInt(events[k][7].substring(6), 10) && yearNum>parseInt(events[k][6].substring(6), 10) )
							{
							month_over[events[k][13]] = get_date_diff(get_s_date,get_e_date) + '--' + events[k][4] + '::' + daycounter + '__'+ events[k][16] + '**' + events[k][15] ;
							}
						
						
						}	
					
					calendarString += '<div id="td-day-'+daycounter+'" align="center" class="td in_scope ui-widget-content"><div data-event-ids="'+ event_ids +'" data-date="'+ monthNum_leading_zero +'/'+ daycounter_leading_zero +'/'+ yearNum +'" class="day_num ui-state-default" data-month="'+ monthNum +'" data-year="'+ yearNum +'" data-day="'+ daycounter +'">' + set_day + '</div></div>';
					}
				}
			}
		
		calendarString += '</div>';
		
		}
	calendarString += '<div class="tr">&nbsp;</div>';
	calendarString += '</div>';
	calendarString += ((envato_username!='') ? '<div class="promote"><a href="http://codecanyon.net/user/Basix/portfolio?ref='+ envato_username +'">'+ ((promo_text) ? promo_text : 'Powered by X-Calendar') +'</a></div>' : '');
		jQuery('div#calendar').html(calendarString);
	repeat_event.sort();
	for(var d=0;d<=repeat_event.length;d++)
		{
		if(repeat_event[d])
			{
			var args = repeat_event[d].split('--');
			
			var args2 = args[1].split('::');
			
			var args3 = args2[1].split('__');
			
			var args4 = args3[1].split('**')
			
			for(var rep=(parseInt(args3[0]));rep<(parseInt(args[0])+parseInt(args3[0]));rep++)
				{
				if(rep==(parseInt(args3[0])))
					jQuery('#td-day-'+rep).append('<div id="'+ args4[0] +'" style="border-top:3px solid '+ args4[1] +';" class="event_'+args4[0]+' start event_time_line ui-widget-header"><div class="event_inside">'+args2[0]+'</div></div>');	
				else
					jQuery('#td-day-'+rep).append('<div id="'+ args4[0] +'" style="border-top:3px solid '+ args4[1] +';" class="event_'+args4[0]+' repeat_event event_time_line ui-widget-header"><div class="event_inside"><span class="ui-icon ui-icon-carat-1-e"></span>'+args2[0]+'</div></div>');
				}
			
			}
		}
		
	for(var over=0;over<=month_over.length;over++)
		{
		if(month_over[over])
			{
			var over_args = month_over[over].split('--');
			
			var over_args2 = over_args[1].split('::');
			
			var over_args3 = over_args2[1].split('__')
			
			var over_args4 = over_args3[1].split('**')
			
			for(var rep=(parseInt(over_args3[0]));rep>(parseInt(over_args3[0])-parseInt(over_args[0]));rep--)
				{
				jQuery('#td-day-'+rep).append('<div id="'+ over_args4[0] +'" style="border-top:3px solid '+ over_args4[1] +';" class="event_'+over_args4[0]+' repeat_event event_time_line ui-widget-header"><div class="event_inside"><span class="ui-icon ui-icon-carat-1-e"></span>'+over_args2[0]+'</div></div>');	
				}
			
			}
		}
	}
}
function checkevents(day,month,year,week,dayofweek) {
var numevents = 0;
var floater = 0;
	for (var i = 0; i < events.length; i++) {
		if (events[i][0] == "W") {
			if ((events[i][2] == dayofweek)) numevents++;
		}
		else if (events[i][0] == "Y") {
			if ((events[i][2] == day) && (events[i][1] == month)) numevents++;
		}
		else if (events[i][0] == "F") {
			if ((events[i][1] == 3) && (events[i][2] == 0) && (events[i][3] == 0) ) {
				easter(year);
				if (easterday == day && eastermonth == month) numevents++;
			} 
		}
		else if ((events[i][2] == day) && (events[i][1] == month) && (events[i][3] == year)) {
			numevents++;
		}
	}

	if (numevents == 0) {
		return false;
	} else {
		return numevents;
	}
}

jQuery('select[name="switch_theme"]').change(
		function()
			{
			jQuery('link.color_scheme').attr('href',jQuery('#site_url').text() + '/wp-content/plugins/X%20Forms/css/'+ jQuery(this).val() +'/jquery.ui.theme.css');	
			}
		);
	
	jQuery('select[name="switch_layout"]').change(
		function()
			{
			jQuery('.form_container').find('link.layout').attr('href',jQuery('#site_url').text() + '/wp-content/plugins/X%20Forms/css/layout/'+ jQuery(this).val() +'.css');	
			}
		);
		
	jQuery('select[name="switch_brightness"]').change(
		function()
			{
			jQuery('body').removeClass('dark');	
			if(jQuery(this).val()=='dark')
				jQuery('body').addClass('dark');			
			}
		);
changedate('return');
resize_blocks();