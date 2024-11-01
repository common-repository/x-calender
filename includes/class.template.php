<?php
if(!class_exists('IZCal_Template')){
	class IZCal_Template{
		public $page_header, $page_menu, $page_body, $page_footer, $plugin_alias;
		public function print_template(){
			echo $this->page_header.$this->page_body.$this->page_footer;
		}
		public function build_header($title='',$subtitle='',$menu='',$description='',$plugin_alias=''){
			
			$this->page_header   = '<div id="plugin_alias" style="display:none;">';
			$this->page_header  .= $this->plugin_alias;
			$this->page_header  .= '</div>';
			
			$this->page_header  .= '<div id="component_table" style="display:none;">';
			$this->page_header  .= $this->component_table;
			$this->page_header  .= '</div>';
			
			$this->page_header  .= '<div id="edit_Id" style="display:none;">';
			$this->page_header  .= $_REQUEST['Id'];
			$this->page_header  .= '</div>';
			
			$this->page_header  .= '<div id="upload_path" style="display:none;">';
			$this->page_header  .=	ABSPATH.'wp-content/uploads/wa-core/';
			$this->page_header  .= '</div>';
			
			$this->page_header  .= '<div id="modules_uri" style="display:none;">';
			$this->page_header  .=	get_option('siteurl').'/wp-content/modules/';
			$this->page_header  .= '</div>';
			
			$this->page_header  .= '<div id="site_url" style="display:none;">';
			$this->page_header  .=	get_option('siteurl');
			$this->page_header  .= '</div>';
			
			$this->page_header  .= '<div id="upload_uri" style="display:none;">';
			$this->page_header  .=	get_option('siteurl').'/wp-content/uploads/wa-core/';
			$this->page_header  .= '</div>';
			
			$this->page_header  .= '<div class="wrap">';
			
			if($title)
				$this->page_header .= '<h2>';$this->page_header .= $title;$this->page_header .= '</h2>';
				
			if($subtitle)
				$this->page_header .= '<h3>';$this->page_header .= $subtitle;$this->page_header .= '</h3>';
			
			if($description)
				$this->page_header .= '<h5>';$this->page_header .= $description;$this->page_header .= '</h5>';
			
			$this->page_header .= $menu.'<br class="clear" />';
			
			return $this->page_header;
		}
		public function build_body($body=''){
			$this->page_body .= $body;
			return $this->page_body;
		}
		public function build_footer($text=''){
			$this->page_footer  = '<div class="footer">';
			$this->page_footer .= $text;
			$this->page_footer .= '</div>';
			$this->page_footer .= '</div>';
			return $this->page_footer;
		}
		public function build_sidebar_footer($text=''){
			$this->sidebar_footer .= '<div style="clear:both"> </div>';
			$this->sidebar_footer .= '<div class="iz-bottom ieHax">';
			$this->sidebar_footer .= $text;
			$this->sidebar_footer .= '</div>';
		}
	}
}
?>