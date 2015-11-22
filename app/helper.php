<?php

// Don't allow plugin to be loaded directory
if ( !defined( 'ABSPATH' ) )
  exit();

class WpApiAuth_Helper
{
  public function get_token_option_name( $service ) {
    return 'wpapi_token_' . $service;
  }

  public function _( $string ) {
    return __( $string, WP_AUTH_DOMAIN );
  }

  public function e( $string ) {
    return _e( $string, WP_AUTH_DOMAIN );
  }
}
