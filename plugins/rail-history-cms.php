<?php
/**
 * Rail History CMS
 *
 * Custom CMS extensions for Zenphoto, enabling the creation of a dynamic rail history website
 *
 * @author Marcus Wong (wongm)
 * @package plugins
 */

$plugin_description = gettext("Custom CMS extensions for Zenphoto, enabling the creation of a dynamic rail history website.");
$plugin_author = "Marcus Wong (wongm)";
$plugin_version = '1.0.0'; 
$plugin_URL = "https://github.com/wongm/rail-history-cms/";
$plugin_is_filter = 500 | ADMIN_PLUGIN;

zp_register_filter('admin_utilities_buttons', 'railHistoryCMS::addAdminButton', 1000);
zp_register_filter('admin_toolbox_global', 'railHistoryCMS::addGlobalEditLink', 1000);

class railHistoryCMS {
	
	static function addAdminButton($buttons) {
		$buttons[] = array(
						'category'		 => gettext('Admin'),
						'enable'			 => true,
						'button_text'	 => gettext('Rail History CMS'),
						'formname'		 => 'zenphotoTagger_button',
						'action'			 => WEBPATH.'/plugins/rail-history-cms',
						'icon'				 => 'images/pencil.png',
						'title'				 => gettext('Edit pages in the Rail History CMS.'),
						'alt'					 => '',
						'hidden'			 => '',
						'rights'			 => ALBUM_RIGHTS
		);
		return $buttons;
	}
    
    static function addGlobalEditLink() {
    	echo "<li>";
    	printLinkHTML(WEBPATH.'/plugins/rail-history-cms', 'Rail History CMS', NULL, NULL, NULL);
    	echo "</li>";
    }
}
?>