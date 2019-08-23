<?php
defined('ABSPATH') or die('No script kiddies please!');
/**
 * @package Sapricami Simple REST_API
 * @version 0.0.3
 */
/*
Plugin Name: Better REST_APIs for Mobile Apps 
Plugin URI: https://www.sapricami.com/programming/wordpress/wordpress-plugin-better-rest-apis/
Description:  A Simple Rest Api plugin for wordpress build to take mobile app developer's woes away.
Version: 0.0.3
Author: ankursinghagra
Author URI: https://ankursinghagra.github.com/
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

require_once SAPRICAMI_WP_REST_BASE . 'class.sapricami_simple_rest.php';
require_once SAPRICAMI_WP_REST_BASE . 'basic_wp_functions/rest_functions.php';

register_activation_hook( __FILE__, array( 'Sapricami_simple_rest', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Sapricami_simple_rest', 'plugin_deactivation' ) );

add_action( 'init', array( 'Sapricami_simple_rest', 'sapricami_init_plugin') );