<?php
if (!defined('OFFSET_PATH')) {
	define('OFFSET_PATH', 3);
	require_once(dirname(dirname(__FILE__)) . '/zp-core/admin-functions.php');
	if (isset($_GET['action']) && $_GET['action'] == 'clear_railHistoryCMS_cache') {
    	require_once(SERVERPATH.'/'.USER_PLUGIN_FOLDER.'/rail-history-cms-cache.php');
    	static_html_cache_railHistoryCMS::clearHTMLCache();
    	$class = 'messagebox';
    	$msg = gettext('Rail History CMS cache cleared.');
    	header('Location: ' . FULLWEBPATH . '/' . ZENFOLDER . '/admin.php?action=external&msg=' . $msg);
		exitZP();
	}
}

$plugin_is_filter = 5|ADMIN_PLUGIN;
$plugin_description = gettext("Provides cache management utilities for the Rail History CMS cache");
$plugin_author = "Marcus Wong";

zp_register_filter('admin_utilities_buttons', 'cacheManager_railHistoryCMS::overviewbutton');

/**
 *
 * Standard options interface
 * @author Stephen
 *
 */
class cacheManager_railHistoryCMS {

	static function overviewbutton($buttons) {

		$buttons[] = array(
						'category'		 => gettext('Cache'),
						'enable'			 => true,
						'button_text'	 => gettext('Cache manager'),
						'formname'		 => 'cacheManager_button',
						'action'			 => WEBPATH . '/' . ZENFOLDER . '/' . PLUGIN_FOLDER . '/cacheManager/cacheImages.php?page=overview&tab=images',
						'icon'				 => 'images/cache.png',
						'alt'					 => '',
						'hidden'			 => '',
						'rights'			 => ADMIN_RIGHTS,
						'title'				 => gettext('Finds images that have not been cached and creates the cached versions.')
		);
		$buttons[] = array(
						'category'=>gettext('Cache'),
						'enable'=>true,
						'button_text'=>gettext('Purge CMS cache'),
						'formname'=>'clearcache_button',
						'action'=>WEBPATH . "/" . USER_PLUGIN_FOLDER.'/rail-history-cms-cache-manager.php?action=clear_railHistoryCMS_cache',
						'icon'=>'images/edit-delete.png',
						'title'=>gettext('Clear the static Rail History CMS cache. HTML pages will be re-cached as they are viewed.'),
						'alt'=>'',
						'hidden'=> '<input type="hidden" name="action" value="clear_railHistoryCMS_cache">',
						'rights'=> ADMIN_RIGHTS,
						'XSRFTag'=>'clear_railHistoryCMS_cache'
									);
		if (class_exists('RSS')) {
			$buttons[] = array(
							'XSRFTag'			 => 'clear_cache',
							'category'		 => gettext('Cache'),
							'enable'			 => true,
							'button_text'	 => gettext('Purge RSS cache'),
							'formname'		 => 'purge_rss_cache.php',
							'action'			 => WEBPATH . '/' . ZENFOLDER . '/admin.php?action=clear_rss_cache',
							'icon'				 => 'images/edit-delete.png',
							'alt'					 => '',
							'title'				 => gettext('Delete all files from the RSS cache'),
							'hidden'			 => '<input type="hidden" name="action" value="clear_rss_cache" />',
							'rights'			 => ADMIN_RIGHTS
			);
		}
		$buttons[] = array(
						'category'		 => gettext('Cache'),
						'enable'			 => true,
						'button_text'	 => gettext('Purge HTML cache'),
						'formname'		 => 'clearcache_button',
						'action'			 => WEBPATH . '/' . ZENFOLDER . '/admin.php?action=clear_html_cache',
						'icon'				 => 'images/edit-delete.png',
						'title'				 => gettext('Clear the static HTML cache. HTML pages will be re-cached as they are viewed.'),
						'alt'					 => '',
						'hidden'			 => '<input type="hidden" name="action" value="clear_html_cache">',
						'rights'			 => ADMIN_RIGHTS,
						'XSRFTag'			 => 'ClearHTMLCache'
		);
		
		$buttons[] = array(
						'category'		 => gettext('Cache'),
						'enable'			 => true,
						'button_text'	 => gettext('Purge search cache'),
						'formname'		 => 'clearcache_button',
						'action'			 => WEBPATH . '/' . ZENFOLDER . '/admin.php?action=clear_search_cache',
						'icon'				 => 'images/edit-delete.png',
						'title'				 => gettext('Clear the static search cache.'),
						'alt'					 => '',
						'hidden'			 => '<input type="hidden" name="action" value="clear_search_cache">',
						'rights'			 => ADMIN_RIGHTS,
						'XSRFTag'			 => 'ClearSearchCache'
		);
		return $buttons;
	}
}

?>
