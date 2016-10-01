<?php

//====================URL==========================
define('ZMOVIES_PLUGIN_URL'		, plugin_dir_url(__FILE__));
define('ZMOVIES_PUBLIC_URL'		, ZMOVIES_PLUGIN_URL . 'public');
define('ZMOVIES_FILE_URL'		, ZMOVIES_PUBLIC_URL . '/files');
define('ZMOVIES_CSS_URL'		, ZMOVIES_PUBLIC_URL . '/css');
define('ZMOVIES_IMAGES_URL'		, ZMOVIES_PUBLIC_URL . '/images');
define('ZMOVIES_JS_URL'			, ZMOVIES_PUBLIC_URL . '/js');
define('ZMOVIES_JS_PLAYER'		, ZMOVIES_PUBLIC_URL . '/jwplayer');

//====================PATH==========================
if(!defined('DS')){
    define('DS'								, DIRECTORY_SEPARATOR);
}
// define('ZMOVIES_PLUGIN_PATH'			, plugin_dir_path(__FILE__));
define('ZMOVIES_PLUGIN_PATH'			, __DIR__ . DS);
define('ZMOVIES_CONFIG_PATH'			, ZMOVIES_PLUGIN_PATH . 'configs');
define('ZMOVIES_CONTROLLER_PATH'		, ZMOVIES_PLUGIN_PATH . 'controllers');
define('ZMOVIES_HELPER_PATH'			, ZMOVIES_PLUGIN_PATH . 'helpers');
define('ZMOVIES_INCLUDE_PATH'		    , ZMOVIES_PLUGIN_PATH . 'includes');
define('ZMOVIES_MODELS_PATH'			, ZMOVIES_PLUGIN_PATH . 'models');
define('ZMOVIES_VENDOR_PATH'			, ZMOVIES_PLUGIN_PATH . 'vendor');
define('ZMOVIES_GSRC_PATH'			   , ZMOVIES_VENDOR_PATH . DS . 'src');

define('ZMOVIES_PUBLIC_PATH'			, ZMOVIES_PLUGIN_PATH . 'public');
define('ZMOVIES_FILE_PATH'			    , ZMOVIES_PUBLIC_PATH . DS . 'files');
// define('ZMOVIES_UPLOAD_PATH'			    , WP_CONTENT_URL . DS . 'uploads' . DS . 'zvideo');
define('ZMOVIES_UPLOAD_URL'			    , WP_CONTENT_URL . '/uploads');
define('ZMOVIES_UPLOAD_PATH'			, WP_CONTENT_DIR . DS . 'uploads');

define('ZMOVIES_TEMPLATE_PATH'		    , ZMOVIES_PLUGIN_PATH . 'templates');
define('ZMOVIES_VALIDATE_PATH'		    , ZMOVIES_PLUGIN_PATH . 'validates');
define('ZMOVIES_LIBRARY_PATH'		    , ZMOVIES_PLUGIN_PATH . 'library');

define('ZMOVIES_EXTENDS_PATH'		    , ZMOVIES_PLUGIN_PATH . 'extends');
define('ZMOVIES_SHORTCODE_PATH'		    , ZMOVIES_EXTENDS_PATH . DS . 'shortcodes');
define('ZMOVIES_WIDGET_PATH'			, ZMOVIES_EXTENDS_PATH . DS . 'widgets');

//====================ORTHER==========================
define('ZMOVIES_PREFIX'              , 'ZMOVIES_');
define('ZMOVIES_PLUGIN_NAME'         , 'ZMOVIES');
define('ZMOVIES_PLUGIN_VERSION'      , '1.0');
define('ZMOVIES_MENU_SLUG'           , 'zvideos-manager');
define('ZMOVIES_SETTING_OPTION'		 , 'zvideos-setting-option');
define('ZMOVIES_DOMAIN_LANGUAGE'	 , 'zvideos');
define('ZMOVIES_ADMIN_MENU'			, 0);
define('ZMOVIES_ADMIN_SHOW_NOTICE'	, 0);

//====================OPTION===========================


global $table_prefix;
define('ZMOVIES_TABLE_PREFIX'		, $table_prefix);
define('ZMOVIES_TABLE_SHORTCODE'    , ZMOVIES_TABLE_PREFIX . 'zshortcode');
define('ZMOVIES_TABLE_ADS'         , ZMOVIES_TABLE_PREFIX . 'zads');

