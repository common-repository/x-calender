<?php
/*
Plugin Name: X Calendar Express
Plugin URI:
Plugin Prefix: wap_ 
Module Ready: Yes
Plugin TinyMCE: popup
Description: This is a free version of a drag and drop events calendar that is more then this in every way :) Please download the pro version - a drag and drop events calendar from the The AUTHOR LINK below. thanks
Author: Basix
Version: 1.0
Author URI: http://codecanyon.net/item/nexevents-drag-drop-wordpress-events-calendar/8762860?ref=Basix
License: GPL
*/
error_reporting(1);
require( dirname(__FILE__) . '/includes/includes.php');
/***************************************/
/**********  Configuration  ************/
/***************************************/
class XCalendarExpress_Config{
	/*************  General  ***************/
	/************  DONT EDIT  **************/
	/* The displayed name of your plugin */
	public $plugin_name;
	/* The alias of the plugin used by external entities */
	public $plugin_alias;
	/* Enable or disable external modules */
	public $enable_modules;
	/* Plugin Prefix */
	public $plugin_prefix;
	/* Plugin table */
	public $plugin_table, $component_table;
	/* Admin Menu */
	public $plugin_menu;
	/* Add TinyMCE */
	public $add_tinymce;
	/************* Database ****************/	
	/* Sets the primary key for table created above */
	public $plugin_db_primary_key = 'Id';
	/* Database table fields array */
	public $plugin_db_table_fields = array
			(
			'title'						=>	'text',
			'start_date'				=>	'text',
			'end_date'					=>	'text',
			'start_time'				=>	'text',
			'end_time'					=>	'text',
			'venue'						=>	'text',
			'address'					=>	'text',
			'gps_coordinates'			=>	'text',
			'google_map'				=>	'text',
			'description'				=>	'longtext',
			'add_registration_form'		=>	'text',
			'send_reminder'				=>	'text',
			'event_color'				=>	'text',
			'recurring'					=>	'text',
			'repeat_event'				=>	'text',
			'repeat_until'				=>	'text',
			'exclude_weekends'			=>	'text',
			'exclude_dates'				=>	'text',
			'exclude_days'				=>	'text',
			'include_days'				=>  'text',
			'sort_date'					=>	'text',
			'monthly_days'				=> 	'text',
			'monthly_conditions_num'	=> 	'text',
			'monthly_conditions_days'	=> 	'text',
			'xforms_Id'					=> 	'text',
			'entry_limit'				=> 	'text'
			
			);	
	
	/************* Admin Menu **************/
	public function build_plugin_menu(){
	
		$plugin_alias  = $this->plugin_alias;
		$plugin_name  = $this->plugin_name;
				
		$this->plugin_menu = array
			(
			$this->plugin_name => array
				(
				'menu_page'	=>	array
					(
					'page_title' 	=> $this->plugin_name,
					'menu_title' 	=> $this->plugin_name,
					'capability' 	=> 'administrator',
					'menu_slug' 	=> 'WA-'.$plugin_alias.'-main',
					'function' 		=> 'XCalendarExpress_main_page',
					'icon_url' 		=> WP_PLUGIN_URL.'/x-calender/images/menu_icon.png',
					'position '		=> ''
					)
				)			
			);
	}
	
	public function __construct()
		{ 
		$header_info = XCore::get_file_headers(dirname(__FILE__).'/main.php');
		$this->plugin_name 		= $header_info['Plugin Name'];
		$this->enable_modules 	= ($header_info['Module Ready']='Yes') ? true : false ;
		$this->plugin_alias		= XCore::format_name($this->plugin_name);
		$this->plugin_prefix	= $header_info['Plugin Prefix'];
		$this->plugin_table		= $this->plugin_prefix.$this->plugin_alias;
		$this->component_table	= $this->plugin_table;
		$this->add_tinymce		= $header_info['Plugin TinyMCE'];
		$this->build_plugin_menu(); 
		}
}
/***************************************/
/*************  Hooks   ****************/
/***************************************/
add_action('wp_ajax_XCalendarExpress_tinymce_window', 'XCalendarExpress_tinymce_window');
/* On plugin activation */
register_activation_hook(__FILE__, 'XCalendarExpress_run_instalation' );
/* Called from page */
add_shortcode( 'xCalendar', 'XCalendar_ui_output' );
/* Build admin menu */
add_action('admin_menu', 'XCalendarExpress_main_menu');
/* Add action button to TinyMCE Editor */
add_action('init', 'XCalendarExpress_add_mce_button');
/* TinyMCE Editor dependancy */
add_filter('admin_head','XCalendarExpress_TinyMCE');
/***************************************/
/*********  Hook functions   ***********/
/***************************************/
/* Convert menu to WP Admin Menu */
function XCalendarExpress_main_menu(){
	$config = new XCalendarExpress_Config();
	IZC_Admin_menu::build_menu($config->plugin_name);
}
/* Called on plugin activation */
function XCalendarExpress_run_instalation(){
	$config = new XCalendarExpress_Config();
	$instalation = new IZC_Instalation();
	$instalation->component_name 			=  $config->plugin_name;
	$instalation->component_prefix 			=  $config->plugin_prefix;
	$instalation->component_alias			=  'x_calendar';
	$instalation->component_default_fields	=  $config->default_fields;
	$instalation->component_menu 			=  $config->plugin_menu;	
	$instalation->db_table_fields			=  $config->plugin_db_table_fields;
	$instalation->db_table_primary_key		=  $config->plugin_db_primary_key;
	$instalation->run_instalation('full');
	add_option( 'x-calendar-settings' , 	array( '' ) );
}

/* Add action button to TinyMCE Editor */
function XCalendarExpress_add_mce_button() {
	add_filter("mce_external_plugins", "XCalendarExpress_tinymce_plugin");
 	add_filter('mce_buttons', 'XCalendarExpress_register_button');
}
/* register button to be called from JS */
function XCalendarExpress_register_button($buttons) {
   array_push($buttons, "separator", "xcalendar");
   return $buttons;
}
/* Send request to JS */
function XCalendarExpress_tinymce_plugin($plugin_array) {
   $plugin_array['xcalendar'] = WP_PLUGIN_URL.'/x-calender/tinyMCE/plugin.js';
   return $plugin_array;
}
/* Popup */
function XCalendarExpress_tinymce_window(){
	include_once( dirname(__FILE__).'/includes/window.php');
    die();
}
/* add needed JS to TinyMCE */
function XCalendarExpress_TinyMCE() {
	wp_enqueue_script( 'post' );
}	
/***************************************/
/*********   Admin Pages   *************/
/***************************************/
//Landing page
function XCalendarExpress_main_page(){
	
	global $wpdb;
	
	$config 	= new XCalendarExpress_Config();
	$template 	= new IZCal_Template();
	
	remove_filter('mce_buttons', 'XForms_register_button');
	
	$events = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wap_x_calendar WHERE recurring=0 ORDER BY title ASC, sort_date ASC');
	/******************************************/
	/*********   TINYMCE EDITOR   *************/
	/******************************************/
	$calendar_settings = get_option( 'x-calendar-settings' , 	array( '' ) );
	
	$output_be .= '<div class="wrap add_event_editor x-calendar-editor" style="display:none;">';
		//$output_be .= '<div style="clear:both;">&nbsp;</div><div class="icon32 icon32-posts-page" id="icon-edit-pages"><br></div>';
		//$output_be .= '<h2>Add new Event</h2>';
			$output_be .= '<form id="post" method="post" action="" name="post">';
				$output_be .= '<div id="poststuff">';
					/************** Right column *****************/
					$output_be .= '<div id="post-body" class="metabox-holder columns-2">';
						$output_be .= '<div id="post-body-content">';
							$output_be .= '<div id="titlediv">';
								$output_be .= '<div id="titlewrap">';
									$output_be .= '<label id="title-prompt-text" class="hide-if-no-js" for="title" style="">Enter Event tiltle here</label>';
									$output_be .= '<input id="title" type="text" autocomplete="off" value="" tabindex="1" size="30" name="subject">';
								$output_be .= '</div>';
							$output_be .= '<div class="inside">';
								$output_be .= '<div id="edit-slug-box"></div>';
							$output_be .= '</div>';
						$output_be .= '</div>';
						$output_be .= '<div id="postdivrich" class="postarea">';
							/************** Output before editor *****************/
							echo $output_be;
							$content = '';
							$editor_id = 'xcalendar_event_description';
							/************** Show Editor *****************/
							wp_editor( $content, $editor_id );
							/************** Editor footer *****************/
							$output_ae .='<table id="post-status-info" cellspacing="0">';
								$output_ae .='<tbody>';
									$output_ae .='<tr>';
										$output_ae .='<td id="wp-word-count">';
											$output_ae .='Word count:';
											$output_ae .='<span class="word-count">0</span>';
										$output_ae .='</td>';
										$output_ae .='<td class="autosave-info">';
											$output_ae .='<span class="autosave-message">&nbsp;</span>';
										$output_ae .='</td>';
									$output_ae .='</tr>';
								$output_ae .='</tbody>';
							$output_ae .='</table>';
						$output_ae .='</div>';
					$output_ae .= '</div>';
					/************** Left column *****************/
					$output_ae .= '<div id="postbox-container-1" class="postbox-container">';
						
						
						/************** Set date and time *****************/
						$output_ae .= '<div id="slugdiv" class="postbox hide-if-js" style="display:block;" >';
							$output_ae .= '<div class="handlediv" title="Click to toggle">';
								$output_ae .= '<br>';
							$output_ae .= '</div>';
							$output_ae .= '<h3 class="hndle">';
								$output_ae .= '<span>Event Booking</span>';
							$output_ae .= '</h3>';
							$output_ae .= '<div class="inside">';
							if ( is_plugin_active( 'X Forms/main.php' ) && function_exists('XForms_ui_output') )
								{
								$xforms = $wpdb->get_results('Select * FROM '.$wpdb->prefix.'wap_x_forms');
								
								$output_ae .= '<label>Select X Form</label>';
								$output_ae .= '<select name="xforms_Id">';
									$output_ae .= '<option value="0" selected="selected">----  Disable bookings  ----</option>';	
									foreach($xforms as $xform)
										$output_ae .= '<option value="'.$xform->Id.'">'.IZC_Database::get_title($xform->Id,'wap_x_forms').'</option>';
											
								$output_ae .= '</select>';
								}
							else
								{
								$output_ae .= '<p><strong>X Forms not found!</strong></p>';
								$output_ae .= '<a href="http://codecanyon.net/item/x-forms-wordpress-form-creator-plugin/5214711?ref=Basix" class="xforms_logo"></a>';
								$output_ae .= '<p>Get <a href="http://codecanyon.net/item/x-forms-wordpress-form-creator-plugin/5214711?ref=Basix">X Forms</a> now to create booking forms for events and so much more. </p>';
								}
								
								
							$output_ae .= '</div>';
						$output_ae .= '</div>';
						/************** Event Settings *****************/
						
						$output_ae .= '<div id="slugdiv" class="postbox hide-if-js" style="display:block;" >';
							$output_ae .= '<div class="handlediv" title="Click to toggle">';
								$output_ae .= '<br>';
							$output_ae .= '</div>';
							$output_ae .= '<h3 class="hndle">';
								$output_ae .= '<span>Event Settings</span>';
							$output_ae .= '</h3>';
							$output_ae .= '<div class="inside">';
								$output_ae .= '<div class="input_holder"><label>Event Color</label><input type="text" class="color current" name="event_color" id="color" value=""> <p class="upgrade_to_pro">Event color coating is only available in <a href="http://codecanyon.net/item/x-calendar-wordpress-calendar-plugin/6576022?ref=Basix">NEX-Events the pro version - a drag and drop events calendar</a></p></div>';
								$output_ae .= '<div class="input_holder"><label>Recurring Event?</label><input type="radio" class="radio" name="recurring" id="recurring" value="1">&nbsp;Yes&nbsp;&nbsp;<input type="radio" class="radio" name="recurring" id="recurring" value="0" checked="checked">&nbsp;No</div>';
								
									$output_ae .= '<div class="recurring_settings"><p class="upgrade_to_pro">Recurring events is only available in <a href="http://codecanyon.net/item/x-calendar-wordpress-calendar-plugin/6576022?ref=Basix">NEX-Events the pro version - a drag and drop events calendar</a></p>';
										$output_ae .= '<strong>Recurring Setup</strong>';
										$output_ae .= '<div class="input_holder"><label>Repeat</label>
															<select name="repeat_event">
																<option value="daily" selected="selected">Daily</option>
																<option value="weekly">Weekly</option>
																<option value="monthly">Monthly</option>
															</select>';
										
										$output_ae .= '</div>';
										$output_ae .= '<div class="daily_settings">';
												$output_ae .= '<div class="input_holder">';
													$output_ae .= '<label>Exclude days</label>';
													$output_ae .= '<select name="exclude_days[]">';
														$output_ae .= '<option value="mon">Monday</option>';
														$output_ae .= '<option value="tue">Tuesday</option>';
														$output_ae .= '<option value="wed">Wednesday</option>';
														$output_ae .= '<option value="thu">Thursday</option>';
														$output_ae .= '<option value="fri">Friday</option>';
														$output_ae .= '<option value="sat">Saturday</option>';
														$output_ae .= '<option value="sun">Sunday</option>';
													$output_ae .= '</select>';
											$output_ae .= '</div>';
										$output_ae .= '</div>';
										
										$output_ae .= '<div class="weekly_settings">';
												$output_ae .= '<div class="input_holder">';
													$output_ae .= '<div class="setting_holder">';
														$output_ae .= '<label>Repeat on every</label>';
														$output_ae .= '<select multiple="multiple" name="include_days[]">';
															$output_ae .= '<option value="mon">Monday</option>';
															$output_ae .= '<option value="tue">Tuesday</option>';
															$output_ae .= '<option value="wed">Wednesday</option>';
															$output_ae .= '<option value="thu">Thursday</option>';
															$output_ae .= '<option value="fri">Friday</option>';
															$output_ae .= '<option value="sat">Saturday</option>';
															$output_ae .= '<option value="sun">Sunday</option>';
														$output_ae .= '</select>';
													$output_ae .= '</div>';
											$output_ae .= '</div>';
										$output_ae .= '</div>';
										
										$output_ae .= '<div class="monthly_settings">';
												$output_ae .= '<div class="input_holder">';
													$output_ae .= '<div class="setting_holder">';
														$output_ae .= '<label>Repeat on day(s)</label>';
														$output_ae .= '<select multiple="multiple" name="monthly_days[]">';
															for($days=1;$days<=31;$days++)
																$output_ae .= '<option value="'.$days.'">'.$days.'</option>';
														$output_ae .= '</select>';
													$output_ae .= '</div>';
													$output_ae .= '<div class="setting_holder">';
														$output_ae .= '<label>And/or repeat on every</label>';
														$output_ae .= '<select multiple="multiple" name="monthly_conditions_num[]">';
																	$output_ae .= '<option value="1">1st</option>';
																	$output_ae .= '<option value="2">2nd</option>';
																	$output_ae .= '<option value="3">3rd</option>';
																	$output_ae .= '<option value="4">4th</option>';
														$output_ae .= '</select>';
													$output_ae .= '</div>';	
													$output_ae .= '<div class="setting_holder">';
														$output_ae .= '<select multiple="multiple" name="monthly_conditions_days[]">';
																	$output_ae .= '<option value="mon">Monday</option>';
																	$output_ae .= '<option value="tue">Tuesday</option>';
																	$output_ae .= '<option value="wed">Wednesday</option>';
																	$output_ae .= '<option value="thu">Thursday</option>';
																	$output_ae .= '<option value="fri">Friday</option>';
																	$output_ae .= '<option value="sat">Saturday</option>';
																	$output_ae .= '<option value="sun">Sunday</option>';
														$output_ae .= '</select>';
													$output_ae .= '</div>';
											$output_ae .= '</div>';
										$output_ae .= '</div>';
										
										$output_ae .= '<div class="input_holder"><label>Repeat Until</label><input name="repeat_until" class="repeat_until" type="text" value=""><p class="description">Max 2 years (end of next year)</p></div>';
										$output_ae .= '<div class="input_holder"><label>Exclude dates</label><input name="exclude_dates" class="multi-dates" type="text" value=""></div>';
											
									$output_ae .= '</div>';
								$output_ae .= '</div>';
						$output_ae .= '</div>';
						/************** Set date and time *****************/
						$output_ae .= '<div id="slugdiv" class="postbox hide-if-js" style="display:block;" >';
							$output_ae .= '<div class="handlediv" title="Click to toggle">';
								$output_ae .= '<br>';
							$output_ae .= '</div>';
							$output_ae .= '<h3 class="hndle">';
								$output_ae .= '<span>Set Date and Time</span>';
							$output_ae .= '</h3>';
							$output_ae .= '<div class="inside">';
								$output_ae .= '<div class="input_holder"><label>Starting Date</label><input name="start_date" class="datepicker" type="text" value=""></div>';
								$output_ae .= '<div class="input_holder"><label>End date</label><input name="end_date" type="text" class="datepicker" value="" disabled="disabled"><p class="upgrade_to_pro">End date is only available in <a href="http://codecanyon.net/item/x-calendar-wordpress-calendar-plugin/6576022?ref=Basix">NEX-Events the pro version - a drag and drop events calendar</a></p></div>';
								$output_ae .= '<div class="input_holder"><label>Starting time </label><input name="start_time" type="text" class="timepicker" value=""></div>';
								$output_ae .= '<div class="input_holder"><label>End time:</label><input name="end_time" type="text" class="timepicker" value=""></div>';
							$output_ae .= '</div>';
						$output_ae .= '</div>';
						/************** Set Venue details *****************/
						$output_ae .= '<div id="slugdiv" class="postbox hide-if-js" style="display:block;" >';
							$output_ae .= '<div class="handlediv" title="Click to toggle">';
								$output_ae .= '<br>';
							$output_ae .= '</div>';
							$output_ae .= '<h3 class="hndle">';
								$output_ae .= '<span>Venue Details</span>';
							$output_ae .= '</h3>';
							$output_ae .= '<div class="inside">';
								$output_ae .= '<div class="input_holder"><label>Venue Name</label><input  name="venue" type="text" value=""></div>';
								$output_ae .= '<div class="input_holder"><label>Address <small><em>(Google Map)</em></small></label><input name="google_map" type="text" value=""><p class="upgrade_to_pro">Google Maps is only available in <a href="http://codecanyon.net/item/x-calendar-wordpress-calendar-plugin/6576022?ref=Basix">NEX-Events the pro version - a drag and drop events calendar</a></p></div>';
							$output_ae .= '</div>';
						$output_ae .= '</div>';
						/************** Visibility *****************/
					/*	$output_ae .= '<div id="slugdiv" class="postbox hide-if-js" style="display:block;" >';
							$output_ae .= '<div class="handlediv" title="Click to toggle">';
								$output_ae .= '<br>';
							$output_ae .= '</div>';
							$output_ae .= '<h3 class="hndle">';
								$output_ae .= '<span>Set visibility</span>';
							$output_ae .= '</h3>';
							$output_ae .= '<div class="inside">';
								$output_ae .= '<div class="input_holder"><input  name="event_visibility" type="radio" value="private">&nbsp;Private</div>';
								$output_ae .= '<div class="input_holder"><input  name="event_visibility" type="radio" value="public" checked="checked">&nbsp;Public</div>';
							$output_ae .= '</div>';
						$output_ae .= '</div>';*/
						$output_ae .= '<input type="button" accesskey="p" tabindex="5" value="Add Event" class="button-primary add_event" id="send_mail" name="send_mail">';
					$output_ae .= '</div>';
				$output_ae .= '</div>';
			$output_ae .= '</form>';
			$output_ae .= '<div style="clear:both;">&nbsp;</div>';
		$output_ae .= '</div>';
	$output_ae .= '</div>';
	/************** Output after editor *****************/
	echo  $output_ae;
	$color_shemes = array(
		'default',
		'black-tie',
		'blitzer',
		'cupertino',
		'dark-hive',
		'dot-luv',
		'eggplant',
		'excite-bike',
		'flick',
		'hot-sneaks',
		'humanity',
		'le-frog',
		'mint-choc',
		'overcast',
		'pepper-grinder',
		'redmond',
		'smoothness',
		'south-street',
		'start',
		'sunny',
		'swanky-purse',
		'trontastic',
		'ui-darkness',
		'ui-lightness',
		'vader'
	);
	
	$output .= '<div class="colmask leftmenu">
					<div class="colright">
						<div class="col1wrap">
							<div class="col1">
								';
							$output .= '<div class="day_view" style="display:none;">';
		
	$output .= '</div>';
	$output .= '<div id="calendar"><div class="load_spinner"></div>Loading...</div>';
	$output .= '<div class="page" style="display:none;">admin-panel</div>';
	$output .= '<div class="editing_Id"></div>';//style="display:none"
	$output .= '<div id="event-info" title="Event Information"></div>';
	$output .= '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>';					
		$output .= '</div>
						</div>
						<div class="col2">
							';
						$output .= '<div class="xcalendar_logo"></div>';
			$output .= '<div class="general_settings">';
			$output .= '<div class="heading">General Calendar Settings</div>';
				$output .= '<div class="color_scheme">';
					$output .= '<label for="change_color_scheme">Choose Color Scheme</label>';
					//$output .= '<select id="change_color_scheme" name="change_color_scheme">';
					//foreach($color_shemes as $color_sheme)
						$output .= '<option value="'.$color_sheme.'" '.(($calendar_settings['color_scheme']==$color_sheme) ? 'selected="selected"' : '').'>'.ucfirst($color_sheme).'</option>';
					$output .= '</select><p class="upgrade_to_pro">Color schemes is only available in <a href="http://codecanyon.net/item/x-calendar-wordpress-calendar-plugin/6576022?ref=Basix">NEX-Events the pro version - a drag and drop events calendar</a></p>';
				$output .= '</div>';
				$output .= '<div class="color_scheme">';
					$output .= '<label for="change_day_display">Day display</label>';
					/*$output .= '<select id="change_day_display" name="change_day_display">';
						$output .= '<option value="full" '.(($calendar_settings['day_display']=='full') ? 'selected="selected"' : '').'>Full (Monday-Sunday)</option>';
						$output .= '<option value="abr" '.(($calendar_settings['day_display']=='abr') ? 'selected="selected"' : '').'>Abrivition (Mon-Sun)</option>';
						$output .= '<option value="fl" '.(($calendar_settings['day_display']=='fl') ? 'selected="selected"' : '').'>First letter(M-S)</option>';
					$output .= '</select>';*/
					$output .= '<p class="upgrade_to_pro">Changing day display is only available in <a href="http://codecanyon.net/item/x-calendar-wordpress-calendar-plugin/6576022?ref=Basix">NEX-Events the pro version - a drag and drop events calendar</a></p>';
				$output .= '</div>';
				$output .= '<div class="color_scheme">';
					$output .= '<label for="change_month_display">Month display</label>';
					/*$output .= '<select id="change_month_display" name="change_month_display">';
						$output .= '<option value="full" '.(($calendar_settings['month_display']=='full') ? 'selected="selected"' : '').'>Full (January-December)</option>';
						$output .= '<option value="abr" '.(($calendar_settings['month_display']=='abr') ? 'selected="selected"' : '').'>Abrivition (Jan-Dec)</option>';
					$output .= '</select>';*/
					$output .='<p class="upgrade_to_pro">Changing month display is only available in <a href="http://codecanyon.net/item/x-calendar-wordpress-calendar-plugin/6576022?ref=Basix">NEX-Events the pro version - a drag and drop events calendar</a></p>';
				$output .= '</div>';
				
				$output .= '<div class="color_scheme">';
					$output .= '<label for="change_month_display">Language</label>';
					/*$output .= '<select id="change_month_display" name="change_month_display">';
						$output .= '<option value="full" '.(($calendar_settings['month_display']=='full') ? 'selected="selected"' : '').'>Full (January-December)</option>';
						$output .= '<option value="abr" '.(($calendar_settings['month_display']=='abr') ? 'selected="selected"' : '').'>Abrivition (Jan-Dec)</option>';
					$output .= '</select>';*/
					$output .='<p class="upgrade_to_pro">Changing to any laguage in only available in <a href="http://codecanyon.net/item/x-calendar-wordpress-calendar-plugin/6576022?ref=Basix">NEX-Events the pro version - a drag and drop events calendar</a></p>';
				$output .= '</div>';
				
			$output .= '</div>';
			$output .= '<div class="promotional_settings">';
				$output .= '<div class="heading">Make Money</div>';
				$output .= '<p class="description">Add Your envato username below and <span style="color:#e7e7e7" >earn 30% (of the total item value)</span> from the first item purchase made by the user following this link. This link will be displayed at the bottom of the calendar on your website. Read about the envato referral program <a href="http://codecanyon.net/make_money/affiliate_program">here</a></p>';
				$output .= '<label for="promo_text">Promational Text</label>';
				$output .= '<input type="text" id="promo_text" name="promo_text" value="'.(($calendar_settings['promo_text']) ? $calendar_settings['promo_text'] : 'Powered by X-Calendar' ).'"></label>';
				$output .= '<label for="envato_username">Envato Username</label>';
				$output .= '<input type="text" id="envato_username" name="envato_username" placeholder="Basix" value="'.$calendar_settings['envato_username'].'"></label>';
				
			$output .= '</div>';
			$output .= '<div class="heading">';
					$output .= '<input type="button" name="update_settings" class="button-primary" value="   Save Calendar Settings   ">';
				$output .= '</div>';
				
			$output .= '<div class="promotional_settings">';
				$output .= '<div class="heading">How to use in:<br />Page, Post, sidebar and PHP';
				$output .= '</div><ul>
								   <li>
										<strong>Page or post:</strong> <br />Add shortcode <strong>[xCalendar] to you page/post content</strong>
									</li>
									<li>
										<strong>Sidebar:</strong> <br />Go to widgets and drag X-Calendar to your desired sidebar
									</li>
									<li>
										<strong>PHP:</strong><br /> <strong>&lt;?php <br />echo XCalendar_ui_output(); <br />?&gt;</strong>
									</li>
								</ul>
								';
			$output .= '</div>';
			
			$output .= '
						</div>
					</div>
				</div>';	
	$output .= '<div id="site_url" style="display:none;">'.get_option('siteurl').'</div>';
	$output .= '<div id="xcalender"><link media="all" type="text/css" href="'.get_option('siteurl').'/wp-content/plugins/x-calender/css/ui-themes/default/jquery.ui.theme.css" class="color_scheme" rel="stylesheet"></div>';
	$output .= '';	
		$output .= '<script type="text/JavaScript" language="JavaScript">
		var thisDate = 1;							// Tracks current date being written in calendar
		var wordMonth =  new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		var wordDays = new Array("M","T","W","T","F","S","S");';

			


		$output .= 'var today = new Date();							// Date object to store the current date
		var todaysDay = today.getDay() + 1;					// Stores the current day number 1-7
		var todaysDate = today.getDate();					// Stores the current numeric date within the month
		var todaysMonth = today.getUTCMonth() + 1;				// Stores the current month 1-12
		var todaysYear = today.getFullYear();					// Stores the current year
		var monthNum = todaysMonth;						// Tracks the current month being displayed
		var yearNum = todaysYear;						// Tracks the current year being displayed
		var firstDate = new Date(String(monthNum)+"/1/"+String(yearNum));	// Object Storing the first day of the current month
		var firstDay = firstDate.getUTCDay();					// Tracks the day number 1-7 of the first day of the current month
		var lastDate = new Date(String(monthNum+1)+"/0/"+String(yearNum));	// Tracks the last date of the current month
		var numbDays = 0;
		var calendarString = "";
		var eastermonth = 0;
		var easterday = 0;
		var is_sidebar = '.(($is_sidebar) ? '1' : '0').';
		var promo_text = "";
		var envato_username = "";		
		var ajaxurl ="'.get_option('siteurl').'/wp-admin/admin-ajax.php";';
		$output .= 'events = new Array';
			$output .= '(';
			
				foreach($events as $event)
					{
					$event_date = explode('/',$event->start_date);
						
							$output .= '["","'.$event_date[0].'","'.$event_date[1].'","'.$event_date[2].'","'.$event->title.'","'.$event->venue.'","'.$event->start_date.'","'.$event->end_date.'","'.$event->start_time.'","'.$event->end_time.'","'.$event->description.'","'.$event->gps_coordinates.'","","'.rand(1000000,9999999).'","'.get_option('siteurl').'","'.$event->event_color.'","'.$event->Id.'"],' ;
							
						}
				
							
				$output .= "['','1','1','2211','End of the line','End','','End']";
			$output .= ');
			
			';
	$output .= '</script>';
	if(floatval(get_bloginfo( 'version' ))<3.8)
		wp_enqueue_style('x-calendar-older-version',WP_PLUGIN_URL . '/x-calender/css/older_wp_version.css');
					
	wp_enqueue_script('x-calendar',WP_PLUGIN_URL . '/x-calender/js/calender.js');
	if($is_sidebar)
	wp_enqueue_style('x-calendar-sidebar',WP_PLUGIN_URL . '/x-calender/css/sidebar.css');
	$template -> build_header( '','' ,'','',$config->plugin_alias);
	$template -> build_body($output);
	$template -> build_footer('');	
	$template -> print_template();
}

/***************************************/
/*********   User Interface   **********/
/***************************************/

function XCalendar_ui_output($is_sidebar=''){
	global $wpdb;
	$is_sidebar=true;
	$config 	= new XCalendarExpress_Config();
	wp_enqueue_style('x-calendar-ui',WP_PLUGIN_URL . '/x-calender/css/ui.css');
	wp_enqueue_style('x-calender-admin-styles', WP_PLUGIN_URL .'/x-calender/css/main.css');
	wp_print_styles();
	wp_enqueue_script('xf-form-validation', WP_PLUGIN_URL . '/X%20Forms/js/public.js');
	wp_print_scripts();
	$calendar_settings = get_option( 'x-calendar-settings' , 	array( '' ) );
	
	$events = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wap_x_calendar WHERE recurring=0 ORDER BY title ASC, sort_date ASC');
	
	$output .= '<div id="xcalender"><link media="all" type="text/css" href="'.get_option('siteurl').'/wp-content/plugins/x-calender/css/ui-themes/default/jquery.ui.theme.css" class="color_scheme" rel="stylesheet"></div>';
	$output .= '';
	
	if(isset($_POST['xform_submit']))
		{
		global $wpdb;
		$form_attr = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix.'wap_x_forms WHERE Id = '.$_REQUEST['wa_forms_Id']);		//$output .= '<style type="text/css" title="inline_form_styles">'.$form_attr->visual_settings.'</style>';

			if($form_attr->on_form_submission=='show_message')
				{
				
				$output .= '<div class="ui-state-highlight ui-corner-all" style="margin-top: 15px; padding: 15px">
				<span class="ui-icon ui-icon-info" style="float: left; margin-right: 5px; margin-top:3px"></span>
				'.((strstr($form_attr->on_screen_confirmation_message,'<br />') || strstr($form_attr->on_screen_confirmation_message,'<br>') ) ? $form_attr->on_screen_confirmation_message : nl2br($form_attr->on_screen_confirmation_message)).'
				</div>';
				}
		$output .= '<div style="display:none;">'.$form_attr->google_analytics_conversion_code.'</div>';
		}
		
	$output .= '<div class="day_view" style="display:none;">';
	$output .= '</div>';
	$output .= '<div id="calendar" style="clear:both;"><div class="load_spinner">
	</div>Loading...</div>';
	$output .= '<div class="page" style="display:none;">ui-interface</div>';
	$output .= '<div class="editing_Id"></div>';//style="display:none"
	$output .= '<div id="event-info" title="Event Information"></div>';
	$output .= '<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script><script type="text/JavaScript" language="JavaScript">';
		$output .= '
		var thisDate = 1;							// Tracks current date being written in calendar
		var wordMonth =  new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		var wordDays = new Array("M","T","W","T","F","S","S");';


		$output .= 'var today = new Date();							// Date object to store the current date
		var todaysDay = today.getDay() + 1;					// Stores the current day number 1-7
		var todaysDate = today.getDate();					// Stores the current numeric date within the month
		var todaysMonth = today.getUTCMonth() + 1;				// Stores the current month 1-12
		var todaysYear = today.getFullYear();					// Stores the current year
		var monthNum = todaysMonth;						// Tracks the current month being displayed
		var yearNum = todaysYear;						// Tracks the current year being displayed
		var firstDate = new Date(String(monthNum)+"/1/"+String(yearNum));	// Object Storing the first day of the current month
		var firstDay = firstDate.getUTCDay();					// Tracks the day number 1-7 of the first day of the current month
		var lastDate = new Date(String(monthNum+1)+"/0/"+String(yearNum));	// Tracks the last date of the current month
		var numbDays = 0;
		var calendarString = "";
		var eastermonth = 0;
		var easterday = 0;
		var is_sidebar = '.(($is_sidebar) ? '1' : '0').';
		var promo_text = "'.$calendar_settings['promo_text'].'";
		var envato_username = "'.$calendar_settings['envato_username'].'";
		var ajaxurl ="'.get_option('siteurl').'/wp-admin/admin-ajax.php";';
		$output .= 'events = new Array';
			$output .= '(';
			$events = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'wap_x_calendar ORDER BY title ASC, sort_date ASC');
				foreach($events as $event)
					{
					$event_date = explode('/',$event->start_date);
					$output .= '["","'.$event_date[0].'","'.$event_date[1].'","'.$event_date[2].'","'.$event->title.'","'.$event->venue.'","'.$event->start_date.'","'.$event->end_date.'","'.$event->start_time.'","'.$event->end_time.'","'.$event->description.'","'.$event->gps_coordinates.'","","'.rand(1000000,9999999).'","'.get_option('siteurl').'","'.$event->event_color.'","'.$event->Id.'"],' ;
							
						}
				
							
				$output .= "['','1','1','2211','End of the line','End','','End']";
			$output .= ');
			
			';
	$output .= '</script>';
	
		wp_enqueue_style('x-calendar-sidebar',WP_PLUGIN_URL . '/x-calender/css/sidebar.css');
	wp_enqueue_script('x-calendar',WP_PLUGIN_URL . '/x-calender/js/calender.js');
	wp_enqueue_script('x-calendar-ui',WP_PLUGIN_URL . '/x-calender/js/ui.js');
	return $output;
}

class XCalendarExpress_widget extends WP_Widget{
	public $name,$config;
	public $widget_desc = 'Add X-Calendar to you sidebar(s)';
	
	public $control_options = array('title' => '','id' => '',);
	function __construct(){
		$config 	= new XCalendarExpress_Config();
		$this->config = $config;
		$this->name = $config->plugin_name;
		$widget_options = array('classname' => __CLASS__,'description' => $this->widget_desc);
		parent::__construct( __CLASS__, $this->name,$widget_options , $this->control_options);
	}
	function widget($args, $instance){
		echo XCalendar_ui_output(1);
	}
	public function form( $instance ){
		$placeholders = array();
		foreach ( $this->control_options as $key => $val )
			{
			$placeholders[ $key .'.id' ] = $this->get_field_id( $key);
			$placeholders[ $key .'.name' ] = $this->get_field_name($key );
			if ( isset($instance[ $key ] ) )
				$placeholders[ $key .'.value' ] = esc_attr( $instance[$key] );
			else
				$placeholders[ $key .'.value' ] = $this->control_options[ $key ];
			}
		$tpl  .= '<p><label for="[+title.id+]">Title:</label>
		<input type="text" value="[+title.value+]" name="[+title.name+]" id="[+title.id+]" class="widefat"></p>';
		
		global $wpdb;
		$get_records = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.$this->config->plugin_table.' ORDER BY Id DESC');
		$current_selection = XCalendarExpress_widget_controls::parse('[+record_id.value+]', $placeholders);
		$tpl .= '';
		
		print XCalendarExpress_widget_controls::parse($tpl, $placeholders);
	}
	static function register_this_widget(){
		register_widget(__CLASS__);
	}
} 
class XCalendarExpress_widget_controls {
	static function parse($tpl, $hash){
   	   foreach ($hash as $key => $value)
			$tpl = str_replace('[+'.$key.'+]', $value, $tpl);
	   return $tpl;
	}
} 
if(!function_exists('Basix_dashboard_widget'))
	{
	function Basix_dashboard_widget(){
		wp_enqueue_style ('basix-dashboard',WP_PLUGIN_URL . '/x-calender/css/basix-dashboard.css');
		wp_enqueue_script('basix-dashboard-js',WP_PLUGIN_URL . '/x-calender/js/basix-dashboard.js');

		$output .= '<div class="dashboard_wrapper">';
			$output .= '<div class="item_logo xforms"><a href="http://codecanyon.net/item/x-forms-wordpress-form-creator-plugin/5214711?ref=Basix"></a><div class="cover_image"></div></div>';
			$output .= '<div class="item_logo xcalendar"><a href="http://codecanyon.net/user/Basix/portfolio?ref=Basix"></a><div class="cover_image"></div></div>';
			$output .= '<div class="item_logo xpsk"><a href="http://codecanyon.net/item/xpsk-wordpress-plugin-starter-kit/5741077?ref=Basix"></a><div class="cover_image"></div></div>';
			$output .= '<div class="item_logo empty"><div class="item_wrapper"></div></div>';
			$output .= '<div class="item_logo empty"><div class="item_wrapper"></div></div>';
			$output .= '<div class="item_logo empty"><div class="item_wrapper"></div></div>';
			$output .= '<div class="item_logo empty"><div class="item_wrapper"></div></div>';
			$output .= '<div class="item_logo empty"><div class="item_wrapper"></div></div>';
		$output .= '<div style="clear:both;"></div>';	
		$output .= '</div>';
		
		echo $output;
	}
	
	function Basix_dashboard_setup() {
		
		wp_add_dashboard_widget('basix_dashboard_widget', 'Items by Basix', 'Basix_dashboard_widget');
		
		global $wp_meta_boxes;
		$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
		$wa_form_builder_widget_backup = array('basix_dashboard_widget' => $normal_dashboard['basix_dashboard_widget']);
		unset($normal_dashboard['basix_dashboard_widget']);
		$sorted_dashboard = array_merge($wa_form_builder_widget_backup, $normal_dashboard);
		$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;	
	} 

	add_action('wp_dashboard_setup', 'Basix_dashboard_setup' );
	}

add_action('widgets_init', 'XCalendarExpress_widget::register_this_widget');

?>