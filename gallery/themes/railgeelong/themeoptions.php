<?php

/* Plug-in for theme option handling 
 * The Admin Options page tests for the presence of this file in a theme folder
 * If it is present it is linked to with a require_once call.
 * If it is not present, no theme options are displayed.
 * 
*/

class ThemeOptions {
	
	function ThemeOptions() {
		setThemeOptionDefault('wongm_imagetitle_truncate_length', 64); 
	}
	
	function getOptionsSupported() {
		return array(	gettext('Image title: truncate length') => array('key' => 'wongm_imagetitle_truncate_length', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('Image title in breadcrumb: truncate to this length'))
					);
	}
}
?>
