<?php
/*
Plugin Name: WP API Auth
Plugin URI: https://funteractive.co.jp/
Description: This plugin provide pages to connect many services with apis in wp-admin.
Author: Keisuke Imura
Author URI: https://funteractive.co.jp
Text Domain: wp-api-auth
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Version: 0.1
*/


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

/**
 * Class WpApiAuth
 */
class WpApiAuth
{
  /**
   * WpApiAuth constructor.
   */
  public function __construct(){
    $this->setup();
  }

  /**
   * Setup plugin basic settings.
   */
  private function setup() {
    // Plugin Path
    if ( !defined( 'WP_API_AUTH_DIR' ) ) {
      define( 'WP_API_AUTH_DIR', plugin_dir_path( __FILE__ ) );
    }

    // Autoloader
    require_once ( WP_API_AUTH_DIR . 'vendor/autoload.php' );

    // Option Page
    require_once( WP_API_AUTH_DIR . 'app/admin.php' );

    // Services
    require_once( WP_API_AUTH_DIR . 'app/services/google.php' );
  }
}
