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
$plugin_is_filter = 9 | CLASS_PLUGIN;

zp_register_filter('admin_utilities_buttons', 'railHistoryCMS::addAdminButton');
zp_register_filter('admin_toolbox_global', 'railHistoryCMS::admin_toolbox_global');

// Rail Geelong custom URLs
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^gallery/?$', 'rule' => '%REWRITE% index.php?p=gallery-index [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^gallery/recent/?$', 'rule' => '%REWRITE% index.php?p=recent [L,QSA]');	
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^gallery/%PAGE%/recent/([0-9]*)/?$', 'rule' => '%REWRITE% index.php?p=recent&page=$1 [L,QSA]');	
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^gallery/(.*)/%PAGE%/([0-9]+)/$', 'rule' => '%REWRITE% index.php?album=$1&page=$2 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^gallery/(.*)$', 'rule' => '%REWRITE% index.php?album=$1 [L,QSA]');

$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^rss-feed/?$', 'rule' => '%REWRITE% index.php?p=rss-feed [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^lineguides/(.*)$', 'rule' => '%REWRITE% index.php?p=lineguides [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^region/(.*)/$', 'rule' => '%REWRITE% index.php?p=region&name=$1 [L,QSA]');
	
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^search/?$', 'rule' => '%REWRITE% index.php?p=site-search [L,QSA]');

$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^updates/?$', 'rule' => '%REWRITE% index.php?p=updates [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^updates/%PAGE%/(.*)/$', 'rule' => '%REWRITE% index.php?p=updates&page=$1 [L,QSA]');
	
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^lineguide/?$', 'rule' => '%REWRITE% index.php?p=lineguides [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^lineguide/([A-Za-z0-9\-_]+)/?$', 'rule' => '%REWRITE% index.php?p=lineguide&line=$1 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^lineguide/([A-Za-z0-9\-_]+)/([A-Za-z0-9\-_]+)/?$', 'rule' => '%REWRITE% index.php?p=lineguide&line=$1&section=$2 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^lineguide/([A-Za-z0-9\-_]+)/locations/([A-Za-z0-9\-_]+)/?$', 'rule' => '%REWRITE% index.php?p=lineguide&line=$1&section=locations&sort=$2 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^lineguide/([A-Za-z0-9\-_]+)/diagram/year-([0-9\-_]+)/?$', 'rule' => '%REWRITE% index.php?p=lineguide&line=$1&section=diagram&year=$2 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^lineguide/([A-Za-z0-9\-_]+)/diagram/page-([A-Za-z0-9\-_]+)/?$', 'rule' => '%REWRITE% index.php?p=lineguide&line=$1&section=diagram&page=$2 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^lineguide/([A-Za-z0-9\-_]+)/diagram/page-([A-Za-z0-9\-_]+)/year-([0-9\-_]+)/?$', 'rule' => '%REWRITE% index.php?p=lineguide&line=$1&section=diagram&page=$2&year=$3 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^lineguide/([A-Za-z0-9\-_]+)/diagram/year-([0-9\-_]+)/page-([0-9\-_]+)/?$', 'rule' => '%REWRITE% index.php?p=lineguide&line=$1&section=diagram&year=$2&page=$3 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^lineguide/([A-Za-z0-9\-_]+)/safeworking/([A-Za-z0-9\-_]+)/?$', 'rule' => '%REWRITE% index.php?p=lineguide&line=$1&section=safeworking&year=$2 [L,QSA]');
	
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^location/([0-9]+)/map/$', 'rule' => '%REWRITE% index.php?p=location-aerial&id=$1&view=map [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^location/([0-9]+)/satellite/$', 'rule' => '%REWRITE% index.php?p=location-aerial&id=$1&view=satellite [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^location/([0-9\-_]+)/?$', 'rule' => '%REWRITE% index.php?p=location&id=$1 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^location/([0-9\-_]+)/box/?$', 'rule' => '%REWRITE% index.php?p=location&id=$1 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^location/([A-Za-z0-9\-\'.,_]+)/?$', 'rule' => '%REWRITE% index.php?p=location&name=$1 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^location/([A-Za-z0-9\-\'.,_]+)/box/?$', 'rule' => '%REWRITE% index.php?p=location&box=$1 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^location/([A-Za-z0-9\-\'.,_]+)/([A-Za-z0-9\-\'._]+)/?$', 'rule' => '%REWRITE% index.php?p=location&name=$1&line=$2 [L,QSA]');  
	
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^locations/?=search=?$', 'rule' => '%REWRITE% index.php?p=locations&search=$1 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^locations/?=search=?$&page=([0-9]+)', 'rule' => '%REWRITE% index.php?p=locations&search=$1&page=$2[L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^locations/([A-Za-z0-9\-_]+)/?$', 'rule' => '%REWRITE% index.php?p=locations&type=$1 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^locations/([A-Za-z0-9\-_]+)/([A-Za-z0-9\-_]+)/?$', 'rule' => '%REWRITE% index.php?p=locations&type=$1&sort=$2 [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^locations/(.*)$', 'rule' => '%REWRITE% index.php?p=locations [L,QSA]');
	
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^articles/?$', 'rule' => '%REWRITE% index.php?p=articles [L,QSA]');
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^article/(.*)/?$', 'rule' => '%REWRITE% index.php?p=article&name=$1 [L,QSA]');
	
// Rail Geelong legacy
$_zp_conf_vars['special_pages'][] = array('define' => false, 'rewrite' => '^(.*)\.php$', 'rule' => '%REWRITE% index.php?p=$1 [L,QSA]');

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
    
    static function admin_toolbox_global($zf) {
    	echo "<li>";
    	printLinkHTML(WEBPATH.'/plugins/rail-history-cms/', 'Rail History CMS', NULL, NULL, NULL);
    	echo "</li>";
		return $zf;
    }
}
?>