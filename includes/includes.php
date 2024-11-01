<?php
wp_enqueue_script('jquery');
wp_enqueue_style('jquery-ui');
/***************************************/
/**********  CORE CLASSES  *************/
/***************************************/

	include_once( 'class.core.php');
	include_once( 'class.admin_menu.php');
	include_once( 'class.install.php');
	if(strstr($_REQUEST['page'],'x_calendar_express-main'))
	{
	
	include_once( 'class.template.php');
	wp_enqueue_style('xc-admin-styles', WP_PLUGIN_URL .'/x-calender/css/main.css');
	}
	/***************************************/
	/****************  JS  *****************/
	/***************************************/
	
	wp_enqueue_script('json2');
	
	wp_enqueue_script('wa-jquery-ui-position',get_option('siteurl').'/wp-includes/js/jquery/ui/jquery.ui.position.min.js');
	wp_enqueue_script('wa-jquery-ui-core',get_option('siteurl').'/wp-includes/js/jquery/ui/jquery.ui.core.min.js');
	wp_enqueue_script('wa-jquery-ui-widget',get_option('siteurl').'/wp-includes/js/jquery/ui/jquery.ui.widget.min.js');
	wp_enqueue_script('wa-jquery-ui-tooltip',get_option('siteurl').'/wp-includes/js/jquery/ui/jquery.ui.tooltip.min.js');
	wp_enqueue_script('wa-jquery-ui-datepicker',get_option('siteurl').'/wp-includes/js/jquery/ui/jquery.ui.datepicker.min.js');
	wp_enqueue_script('wa-jquery-ui-dialog',get_option('siteurl').'/wp-includes/js/jquery/ui/jquery.ui.dialog.min.js');
	wp_enqueue_script('wa-jquery-ui-effects',get_option('siteurl').'/wp-includes/js/jquery/ui/jquery.ui.effect.min.js');
	wp_enqueue_script('wa-jquery-ui-button',get_option('siteurl').'/wp-includes/js/jquery/ui/jquery.ui.button.min.js');
	wp_enqueue_script('wa-jquery-ui-timepicker',WP_PLUGIN_URL.'/x-calender/js/jquery.ui.timepicker.js');
	//wp_enqueue_script('iz_json2',  WP_PLUGIN_URL . '/X%20Forms/includes/Core/js/json2.min.js');
	
	wp_register_style('jquery_ui_all', WP_PLUGIN_URL . '/x-calender/css/jquery-ui.min.css');
	wp_enqueue_style('jquery_ui_all');
	
	wp_register_style('jquery_ui_base',  WP_PLUGIN_URL . '/x-calender/css/jquery-ui.css');
	wp_enqueue_style('jquery_ui_base');
	
	/***************************************/
	/****************  CSS  ****************/
	/***************************************/
	wp_enqueue_style('wa-jquery-ui-multiselect-widget', WP_PLUGIN_URL . '/x-calender/css/multi-select.css');
	//
	wp_enqueue_style('jquery-ui-timepicker',  WP_PLUGIN_URL . '/x-calender/css/jquery.ui.timepicker.css');
	wp_enqueue_style('jquery_ui_datepicker',  WP_PLUGIN_URL . '/x-calender/css/jquery.ui.datepicker.css');
	/***************************************/
	/***********  ADMIN ONLY  **************/
	/***************************************/
	//if(is_admin() && ( isset($_GET['page']) && stristr($_GET['page'],'wa'))){
	wp_enqueue_script('wa-multi-date-picker', WP_PLUGIN_URL . '/x-calender/js/jquery-ui.multidatespicker.js');
	wp_enqueue_script('wa-jquery-ui-multiselect-widget', WP_PLUGIN_URL . '/x-calender/js/jquery.multi-select.js');
	wp_enqueue_script('wa-colour-picker', WP_PLUGIN_URL . '/x-calender/js/colorpicker.js');
	wp_enqueue_style('wa-colour-picker', WP_PLUGIN_URL . '/x-calender/css/colorpicker.css');
	
//}

?>