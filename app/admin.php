<?php

// Don't allow plugin to be loaded directory
if ( !defined( 'ABSPATH' ) )
  exit();

new WpApiAuth_Admin();

/**
 * Class WpApiAuth_Admin
 */
class WpApiAuth_Admin
{

  /**
   * WpApiAuth_Admin constructor.
   */
  public function __construct() {
    session_start();

    // Set hooks
    add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
    add_action( 'admin_notices', array( &$this, 'admin_notices' ) );
  }

  /**
   * Add API Setting menu in manage options menu.
   */
  public function admin_menu() {
    add_options_page(
      _( 'WP API Settings' ),
      _( 'WP API Settings' ),
      'manage_options',
      'wp-api-auth',
      array( $this, 'render_admin_page' )
    );
  }

  /**
   * Display notices.
   */
  public function admin_notices() {
    if( $notices = get_option( WP_API_AUTH_NOTICE ) ) {
      $notices = unserialize( $notices );
      var_dump($notices);
      //echo sprintf( '<div class="%s"><p>%s</p></div>', $class, $message );
    }
  }

  /**
   * Get admin page template.
   */
  public function render_admin_page() {
    $this->get_template( 'general' );
  }

  /**
   * Template include helper.
   *
   * @param $name
   */
  public static function get_template( $name ) {
    $path = WP_API_AUTH_DIR . 'app/templates/' . $name . '.php';
    if( file_exists( $path ) ){
      include $path;
    }
  }
}