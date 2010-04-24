<?php

/* Plug-in for theme option handling 
 * The Admin Options page tests for the presence of this file in a theme folder
 * If it is present it is linked to with a require_once call.
 * If it is not present, no theme options are displayed.
 * 
*/

class ThemeOptions {
	
	function ThemeOptions() {
		setThemeOptionDefault('railgeelong_imagetitle_truncate_length', 40); 
	}
	
	function getOptionsSupported() {
		return array(	gettext('Image title: truncate length') => array('key' => 'railgeelong_imagetitle_truncate_length', 'type' => OPTION_TYPE_TEXTBOX, 'desc' => gettext('Image title in breadcrumb: truncate to this length'))
					);
	}
}
?>
