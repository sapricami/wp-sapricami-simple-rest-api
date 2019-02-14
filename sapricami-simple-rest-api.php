<?php
defined('ABSPATH') or die('No script kiddies please!');
/**
 * @package Sapricami Simple REST_API
 * @version 0.0.1
 */
/*
Plugin Name: Sapricami Simple REST_API
Plugin URI: https://www.sapricami.com/
Description:  A Simple Rest Api plugin for wordpress build to take mobile app developer's woes away.
Version: 0.0.1
Author: Sapricami
Author URI: https://www.sapricami.com/
License: GPLv2 or later
Text Domain: sapricami-sample-rest-api
*/

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
define('SAPRICAMI_WP_REST_PATH', __FILE__);
define('SAPRICAMI_WP_REST_BASE', realpath(plugin_dir_path(__FILE__)) . DS);
define('SAPRICAMI_WP_REST_URL', plugin_dir_url(__FILE__));

if (version_compare(phpversion(), '5.6', '<')) {
  add_action(
    'admin_notices',
    function () {
      echo '<div class="notice notice-error is-dismissible"><p><strong>Ultimate WP REST:</strong> Your php version (' . phpversion() . ') is not eligible! Please use version 7.0 or higher</p></div>';
    }
  );
  return;
}
error_reporting(E_ALL & ~E_NOTICE);

require_once SAPRICAMI_WP_REST_BASE . 'class.sapricami_simple_rest.php';
require_once SAPRICAMI_WP_REST_BASE . 'basic_wp_functions/rest_functions.php';

register_activation_hook( __FILE__, array( 'Sapricami_simple_rest', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Sapricami_simple_rest', 'plugin_deactivation' ) );

add_action( 'init', array( 'Sapricami_simple_rest', 'sapricami_init_plugin') );