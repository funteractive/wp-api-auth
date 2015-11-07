<?php

// Don't allow plugin to be loaded directory
if ( !defined( 'ABSPATH' ) )
  exit();

class WpApiAuth_GA
{

  public function __construct() {
    require_once ( WP_API_AUTH_DIR . 'vendor/autoload.php' );

    session_start();
    $this->client = new Google_Client();
    $this->client->setAuthConfigFile( WP_API_AUTH_DIR . 'client_secrets.json' );
    $this->client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/wp-admin/options-general.php?page=wp-api-auth');
    $this->client->setScopes( 'https://www.googleapis.com/auth/analytics.readonly' );

    if( isset( $_GET['code'] ) ) {
      echo $_GET['code']; exit;
    }
  }

  public function render_admin_page() {
    $authUrl = $this->client->createAuthUrl();
    echo '<a href="' . $authUrl . '" target="_blank">auth</a>';
  }
}
