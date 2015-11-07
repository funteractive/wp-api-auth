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
      $this->client->authenticate( $_GET['code'] );
      $_SESSION['access_token'] = $this->client->getAccessToken();
    }
  }

  public function render_admin_page() {
    if( isset( $_SESSION['access_token'] ) ) {
      $data = $this->getUserFromToken( $_SESSION['access_token'] );
      var_dump($data);
    } else {
      $authUrl = $this->client->createAuthUrl();
      echo '<a href="' . $authUrl . '" target="_blank">auth</a>';
    }
  }

  public function getUserFromToken( $token ) {
    $ticket = $this->client->verifyIdToken( $token );
    if ( $ticket ) {
      $data = $ticket->getAttributes();
      return $data; // user ID
    }
    return false;
  }
}
