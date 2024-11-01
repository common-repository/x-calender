// JavaScript Document
jQuery(document).ready(
function ()
	{
	jQuery('.day_num').live('click',
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
					the_date		: jQuery(this).attr('data-date'),
					event_ids		: jQuery(this).attr('data-event-ids')
					};		
				jQuery.post(ajaxurl, data, function(response){
					
					jQuery('div.day_view').html(response);
					
				});
			}
		);
	}
);