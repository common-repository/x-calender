<?php
add_action('wp_ajax_insert_event', array('XCore','insert'));
add_action('wp_ajax_edit_event', array('XCore','update'));
add_action('wp_ajax_delete_event', array('XCore','delete'));

add_action('wp_ajax_update_settings', array('XCore','update_settings'));

add_action('wp_ajax_get_event_info', array('XCore','get_event_info'));
add_action('wp_ajax_nopriv_get_event_info', array('XCore','get_event_info'));

add_action('wp_ajax_day_view', array('XCore','day_view'));
add_action('wp_ajax_nopriv_day_view', array('XCore','day_view'));

class XCore{
	public function insert(){
		global $wpdb;
		$fields 	= $wpdb->get_results("SHOW FIELDS FROM " . $wpdb->prefix . $_POST['table']);
		$field_array = array();
		foreach($fields as $field)
			{
			if(isset($_POST[$field->Field]))
				{
				if(is_array($_POST[$field->Field]))
					$field_array[$field->Field] = json_encode($_POST[$field->Field],JSON_FORCE_OBJECT);
				else
					$field_array[$field->Field] = $_POST[$field->Field];
				}	
			}
		$insert = $wpdb->insert ( $wpdb->prefix . $_POST['table'], $field_array );
		echo mysql_insert_id();
		die();
	}
	public function update(){
		global $wpdb;
		$fields 	= $wpdb->get_results("SHOW FIELDS FROM " . $wpdb->prefix . $_POST['table']);
		$field_array = array();
		foreach($fields as $field)
			{
			if(isset($_POST[$field->Field]))
				{
				if(is_array($_POST[$field->Field]))
					$field_array[$field->Field] = json_encode($_POST[$field->Field],JSON_FORCE_OBJECT);
				else
					$field_array[$field->Field] = $_POST[$field->Field];
				}	
			}
		$update = $wpdb->update ( $wpdb->prefix . $_POST['table'], $field_array, array(	'Id' => $_POST['edit_Id']) );
		echo $_POST['edit_Id'];
		die();
	}
	
	public function delete(){
		global $wpdb;
		$wpdb->query('DELETE FROM ' .$wpdb->prefix. $_POST['table']. ' WHERE Id = '.$_POST['Id']);
		echo 'DELETE FROM ' .$wpdb->prefix. $_POST['table']. ' WHERE Id = '.$_POST['Id'];
		die();
	}
	
	public function get_file_headers($file){
			
		$default_headers = array(			
			'Module Name' 		=> 'Module Name',
			'For Plugin' 		=> 'For Plugin',
			'Module Prefix'		=> 'Module Prefix',
			'Module URI' 		=> 'Module URI',
			'Module Scope' 		=> 'Module Scope',
			
			'Plugin Name' 		=> 'Plugin Name',
			'Plugin TinyMCE' 	=> 'Plugin TinyMCE',
			'Plugin Prefix'		=> 'Plugin Prefix',
			'Plugin URI' 		=> 'Plugin URI',
			'Module Ready' 		=> 'Module Ready',
			
			'Version' 			=> 'Version',
			'Description' 		=> 'Description',
			'Author' 			=> 'Author',
			'AuthorURI' 		=> 'Author URI'
		);
		return get_file_data($file,$default_headers,'module');
	}
	public function format_name($str){
		$str = strtolower($str);		
		$str = str_replace('  ',' ',$str);
		$str = str_replace(' ','_',$str);
		return trim($str);
	}
		
	public function unformat_name($str){
		
		$str = IZC_Functions::format_name($str);
		
		$str = str_replace('u2019','\'',$str);
		$str = str_replace('_',' ',$str);
		$str = ucwords($str);
		return trim($str);
	}
	
	public function get_event_info(){
		global $wpdb;
		
		$event = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wap_x_calendar WHERE Id='.$_POST['event_Id']);
		
		$output = ($_POST['page']=='admin-panel') ? '<div class="update"><div id="'.$_POST['event_Id'].'" class="edit">Edit</div><div id="'.$_POST['event_Id'].'" class="delete">Delete</div></div>' : '';
		$output .= '<div style="clear:both"></div>';
		$s_date_before = explode('/',$event->start_date);
		$s_format_date = date(get_option('date_format'),strtotime($s_date_before[1].'-'.$s_date_before[0].'-'.$s_date_before[2]));
		$e_date_before = explode('/',$event->end_date);
		$e_format_date = date(get_option('date_format'),strtotime($e_date_before[1].'-'.$e_date_before[0].'-'.$e_date_before[2]));
		
		$output .= '<div class="db_details" style="display:none;">';
			$output .= '<div class="event_id">'.$event->Id.'</div>';
			$output .= '<div class="event_color">'.$event->event_color.'</div>';
			$output .= '<div class="event_start_date">'.$event->start_date.'</div>';
			$output .= '<div class="event_start_time">'.$event->start_time.'</div>';
			$output .= '<div class="event_end_time">'.$event->end_time.'</div>';
			
			$output .= '<div class="xforms_id">'.str_replace('\\','',$event->xforms_Id).'</div>';
			
			
		$output .= '</div>';
	if(function_exists('XForms_ui_output') && $event->xforms_Id!=0)
		$output .= '<div class="event_content">';
			$output .= '<div class="event_details">';
				if($event->start_date)
					{
					$output .= '<div class="start_date">';
						$output .= $s_format_date ;
					$output .= '</div>';
					}
				if($event->start_time)
					{
					$output .= '<div class="start_time">';
						$output .= (($event->end_time && $event->end_time!=$event->start_time) ? '<em>From</em> ' : '').$event->start_time.(($event->end_time && $event->end_time!=$event->start_time) ? ' <em>to</em> '.$event->end_time : '');
					$output .= '</div>';
					}
				
			$output .= '</div>';
			$output .= '<div class="event_description">';
				$output .= str_replace('\\','',$event->description);
			$output .= '</div>';
			
			$output .= '<div class="venue_name">';
					$output .= $event->venue;
				$output .= '</div>';
			
	if(function_exists('XForms_ui_output') && $event->xforms_Id!=0)
		$output .= '</div>';
		$output .= '<div class="booking_form">';
		if(function_exists('XForms_ui_output') && $event->xforms_Id!=0)
			{
				$xf_array = array('id' => $event->xforms_Id,'xcalendar'=>1);
				$output .= XForms_ui_output($xf_array);
				
				$output .= '
				<link rel="stylesheet" type="text/css" href='.WP_PLUGIN_URL . '/X%20Forms/css/ui.css />
				<link rel="stylesheet" type="text/css" href='.WP_PLUGIN_URL . '/X%20Forms/css/admin.css />';
			

			//wp_print_styles();	
			}
			$output .= '</div>';
		
		echo $output;
		die();
	}
	public function day_view(){
		global $wpdb;
		
		$event_ids = explode(',',$_POST['event_ids']);
		$wherestr = '';
		$i=0;
		foreach($event_ids as $event_id)
			{
			if($event_id!='')
				{
				$wherestr .= ($i==0) ? 'Id='.$event_id  : ' OR Id='.$event_id;
				$i++;
				}
			}
		
		$events = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wap_x_calendar WHERE '.$wherestr.' ORDER BY sort_date ASC');
		
		$s_date_before = explode('/',$_POST['the_date']);
		$s_format_date = date(get_option('date_format'),strtotime($s_date_before[1].'-'.$s_date_before[0].'-'.$s_date_before[2]));
		
		$output .= '<div class="day_view_header ui-widget-header "><span class="ui-icon ui-icon-circle-triangle-w" title="Back to calendar"></span>&nbsp;&nbsp;&nbsp;'.$s_format_date.'</div>';
			for($i=0;$i<24;$i++)
				{
				$j=$i;
				if($i<10)
					$j = '0'.$i;
				$output .= '<div class="time_holder ui-state-default">';
					$output .= '<div class="time">'.$j.':00</div>';
					foreach($events as $event)
						{
						$get_time = explode(' ',$event->sort_date);
						$get_hour = explode(':',$get_time[1]);	
						if($get_hour[0]==$j)
							$output .= '<div class="event_info_holder" id="'.$event->Id.'">'.$event->title.' <br />'.(($event->end_time) ? '<em><small>from '.$event->start_time.' to '.$event->end_time.'</small></em>' : '').'</div>';
						
						}
					$output .= '<div style="clear:both"></div>';
				$output .= '</div>';
				
				}
		echo $output;
		die();
	}
	
	public function update_settings(){
		update_option('x-calendar-settings' , 	$_POST);
	}
}

?>