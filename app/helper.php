<?php

// Don't allow plugin to be loaded directory
if ( !defined( 'ABSPATH' ) )
  exit();

class WpApiAuth_Helper
{
  public function get_token_option_name( $service ) {
    return 'wpapi_token_' . $service;
  }

  public function get_flash_updated( $msg ) {
    return
      '<div class="updated notice is-dismissible">'
      . nl2br( esc_html( $msg ) )
      . $this->get_dismiss_button()
      . '</div>';
  }

  public function get_flash_error( $msg ) {
    return
      '<div class="error is-dismissible">'
      . nl2br( esc_html( $msg ) )
      . $this->get_dismiss_button()
      . '</div>';
  }

  private function get_dismiss_button() {
    return '<button type="button" class="notice-dismiss"><span class="screen-reader-text">この通知を非表示にする</span></button>';
  }
}
