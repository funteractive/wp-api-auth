<?php

// Don't allow plugin to be loaded directory
if ( !defined( 'ABSPATH' ) )
  exit();

class WpApiAuth_GA
{

  public function __construct() {
    require_once ( WP_API_AUTH_DIR . 'tools/autoload.php' );

    session_start();
    $this->client = new Google_Client();
    $this->client->setAuthConfigFile( WP_API_AUTH_DIR . 'client_secrets.json' );
    $this->client->setScopes( 'https://www.googleapis.com/auth/analytics.readonly' );
    $this->client->setRedirectUri( 'urn:ietf:wg:oauth:2.0:oob' );

    if( isset( $_GET['code'] ) ) {
      echo $_GET['code']; exit;
    } else {
      //$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/wp-admin/options-general.php?page=wp-api-auth';
      $auth_url = $this->client->createAuthUrl();
      //header( 'Location: ' . filter_var( $auth_url, FILTER_SANITIZE_URL ) );
    }
  }

  public function render_admin_page() {
    $authUrl = $this->client->createAuthUrl();
    echo '<a href="' . $authUrl . '" target="_blank">auth</a>';
  }
}
