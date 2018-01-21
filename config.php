<?php
// HTTP
define('HTTP_SERVER', 'http://more-snov.ru/');

// HTTPS
define('HTTPS_SERVER', 'http://more-snov.ru/');

//isMobile
if (is_file('/var/www/u0335492/data/www/more-snov.ru/lib/mobile_detect/Mobile_Detect.php')) {
	require_once('/var/www/u0335492/data/www/more-snov.ru/lib/mobile_detect/Mobile_Detect.php');
	$detect = new Mobile_Detect;
	define('IS_MOBILE', $detect->isMobile());
}

// DIR
define('DIR_APPLICATION', '/var/www/u0335492/data/www/more-snov.ru/catalog/');
define('DIR_SYSTEM', '/var/www/u0335492/data/www/more-snov.ru/system/');
define('DIR_IMAGE', '/var/www/u0335492/data/www/more-snov.ru/image/');
define('DIR_LANGUAGE', '/var/www/u0335492/data/www/more-snov.ru/catalog/language/');
if(IS_MOBILE) { //старый шаблон для мобильной версии
	define('DIR_TEMPLATE', '/var/www/u0335492/data/www/more-snov.ru/catalog/view/theme_mobile/');
} else {
	define('DIR_TEMPLATE', '/var/www/u0335492/data/www/more-snov.ru/catalog/view/theme/');
}
define('DIR_CONFIG', '/var/www/u0335492/data/www/more-snov.ru/system/config/');
define('DIR_CACHE', '/var/www/u0335492/data/www/more-snov.ru/system/storage/cache/');
define('DIR_DOWNLOAD', '/var/www/u0335492/data/www/more-snov.ru/system/storage/download/');
define('DIR_LOGS', '/var/www/u0335492/data/www/more-snov.ru/system/storage/logs/');
define('DIR_MODIFICATION', '/var/www/u0335492/data/www/more-snov.ru/system/storage/modification/');
define('DIR_UPLOAD', '/var/www/u0335492/data/www/more-snov.ru/system/storage/upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'u0335492_moresn');
define('DB_PASSWORD', 'qazxswedc12');
define('DB_DATABASE', 'u0335492_moresn');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');


