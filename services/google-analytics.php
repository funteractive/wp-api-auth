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
    $this->client->setRedirectUri( 'http://' . $_SERVER['HTTP_HOST'] . '/wp-admin/options-general.php?page=wp-api-auth' );
    $this->client->setScopes( Google_Service_Analytics::ANALYTICS_READONLY );
    $this->client->setAccessType( 'offline' );

    if( isset( $_GET['code'] ) ) {
      $this->client->authenticate( $_GET['code'] );
      $_SESSION['access_token'] = $this->client->getAccessToken();
    }
  }

  public function render_admin_page() {
    if( isset( $_SESSION['access_token'] ) && $_SESSION['access_token'] ) {
      try {
        $this->client->setAccessToken( $_SESSION['access_token'] );
        $this->service = new Google_Service_Analytics( $this->client );
        $accounts = $this->service->management_accounts->listManagementAccounts();
        var_dump( $accounts->getItems() );
      } catch( Google_Exception $e ) {
        unset( $_SESSION['access_token'] );
        echo $e->getMessage();
      }
    } else {
      $authUrl = $this->client->createAuthUrl();
      echo '<a href="' . $authUrl . '" target="_blank">auth</a>';
    }
  }

}
