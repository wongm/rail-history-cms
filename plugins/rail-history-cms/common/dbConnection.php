<?php

// zenphoto magic
define('OFFSET_PATH', 4);
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/zp-core/admin-globals.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/zp-core/template-functions.php');
admin_securityChecks(ALBUM_RIGHTS, currentRelativeURL());
setupTheme();

include_once('backend-functions.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . $_zp_themeroot . '/common/formatting-functions.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . $_zp_themeroot . '/common/definitions.php');

?>