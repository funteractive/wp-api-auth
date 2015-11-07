<?php

// Don't allow plugin to be loaded directory
if ( !defined( 'ABSPATH' ) )
  exit();

/**
 * Class WpApiAuth_Google
 */
class WpApiAuth_Google
{

  public function __construct() {
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

  public function get_admin_page_html() {
    if( isset( $_SESSION['access_token'] ) && $_SESSION['access_token'] ) {
      try {
        $this->client->setAccessToken( $_SESSION['access_token'] );
        $this->service = new Google_Service_Analytics( $this->client );

        // Get Google Analytics accounts.
        $accounts = $this->service->management_accounts->listManagementAccounts();
      } catch( Google_Exception $e ) {
        unset( $_SESSION['access_token'] );
        echo $e->getMessage();
      }
    } else {
      $authUrl = $this->client->createAuthUrl();
      echo '<a class="button button-secondary" href="' . $authUrl . '" target="_blank">Authorized Plugin</a>';
    }
  }

}
