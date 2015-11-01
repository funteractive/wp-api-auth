<?php
/*
Plugin Name: WP API Auth
Plugin URI: https://funteractive.co.jp/
Description: This plugin provide pages to connect many services with apis in wp-admin.
Author: FUNTERACTIVE, Inc.
Author URI: https://funteractive.co.jp
Text Domain: wp-api-auth
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Version: 0.1
*/

namespace WpApiAuth;

// Don't allow plugin to be loaded directory
if ( !defined( 'ABSPATH' ) )
  exit();

/**
 * Plugin version
 *
 * @const string
 */
if( !defined( 'WP_API_AUTH_VERSION' ) )
  define('WP_API_AUTH_VERSION', '0.1.0');

new WpApiAuth();
class WpApiAuth
{
  public function __construct() {
    add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
  }

  public function admin_menu() {
    add_options_page( 'API設定', 'API設定', 'manage_options', 'wp-api-auth', array($this, 'render_admin_page') );
  }

  public function render_admin_page() {
    echo 'hogehoge';
  }
}
