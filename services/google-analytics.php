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

    if( isset( $_GET['code'] ) ) {
      $this->client->authenticate( $_GET['code'] );
      $_SESSION['access_token'] = $this->client->getAccessToken();
    }
  }

  public function render_admin_page() {
    if( isset( $_SESSION['access_token'] ) ) {
      $this->service = new Google_Service_Analytics( $this->client );
      $startindex = 1;
      $profiles = $this->service->management_profiles->listManagementProfiles( '~all', '~all', array( 'start-index' => $startindex ) );

      $items = $profiles->getItems();

      $totalresults = $profiles->getTotalResults();
      var_dump($profiles);
      var_dump($items);
      var_dump($totalresults);
    } else {
      $authUrl = $this->client->createAuthUrl();
      echo '<a href="' . $authUrl . '" target="_blank">auth</a>';
    }
  }
}
