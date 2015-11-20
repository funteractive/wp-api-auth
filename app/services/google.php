<?php

// Don't allow plugin to be loaded directory
if ( !defined( 'ABSPATH' ) )
  exit();

/**
 * Class WpApiAuth_Google
 */
class WpApiAuth_Google
{
  private $service_name = 'google';

  private $reset_nonce_action = "wp_api_auth_google_reset_nonce";

  public function __construct() {

    // Include helper class.
    require_once( WP_API_AUTH_DIR . 'app/helper.php' );
    $this->helper = new WpApiAuth_Helper();

    // Set client data.
    $this->set_client();

    if( isset( $_GET['code'] ) ) {
      $this->authenticate( $_GET['code'] );
    }
  }

  public function get_admin_page_html() {
    // When reset token.
    if( isset( $_POST["wp_api_auth_{$this->service_name}_reset"] ) ) {
      if ( !isset( $_POST["wp_api_auth_{$this->service_name}_reset_nonce"] ) || !wp_verify_nonce( $_POST["wp_api_auth_{$this->service_name}_reset_nonce"], $this->reset_nonce_action ) ) {
        die( _( 'It\'s illegal input.' ) );
      }
      $this->reset();

      echo $this->get_authorize_button_html();
    }
    // When have been authorized.
    elseif( isset( $_SESSION['access_token'] ) && $_SESSION['access_token'] ) {
      try {
        $this->client->setAccessToken( $_SESSION['access_token'] );
        $this->service = new Google_Service_Analytics( $this->client );
      } catch( Google_Exception $e ) {
        $this->refresh();
        echo $e->getMessage();
      }

      // Get Google Analytics accounts.
      $accounts = $this->service->management_accounts->listManagementAccounts();
      if( $accounts )
        echo '<p>' . sprintf( _( 'Authorized as %s' ), $accounts->username ) . '</p>';

      $nonce = wp_create_nonce( $this->reset_nonce_action );
      echo '<input type="hidden" name="wp_api_auth_' . $this->service_name . '_reset_nonce" value="' . $nonce . '" />';
      echo '<input type="submit" name="wp_api_auth_' . $this->service_name . '_reset" class="button button-secondary" value="' . _( 'Clear Authorization' ) . '" />';
    }
    else {
      echo $this->get_authorize_button_html();
    }
  }

  /**
   * Set client data.
   */
  private function set_client() {
    $this->client = new Google_Client();
    $this->client->setAuthConfigFile( WP_API_AUTH_DIR . 'client_secrets.json' );
    $this->client->setRedirectUri( 'http://' . $_SERVER['HTTP_HOST'] . '/wp-admin/options-general.php?page=wp-api-auth' );
    $this->client->setScopes( Google_Service_Analytics::ANALYTICS_READONLY );
    $this->client->setAccessType( 'offline' );
    $this->client->setApprovalPrompt( 'force' );
  }

  /**
   * Authenticate and get access token.
   * @param $code
   */
  private function authenticate( $code ) {
    $this->client->authenticate( $code );
    try {
      $access_token = $this->client->getAccessToken();
      if( $access_token ) {
        # TODO : Show update message.
        $notices = array(
          'class' => 'error',
          'message' => '認証完了！'
        );
        add_option( WP_API_AUTH_NOTICE, serialize( $notices ) );

        $this->save_access_token( $access_token );
        $_SESSION['access_token'] = $access_token;
      }
    } catch( Google_Exception $e ) {
      echo $this->helper( $e->getMessage() );
    }
  }

  /**
   * Save access_token into DB.
   * @param $access_token
   */
  private function save_access_token( $access_token ) {
    $option_name = $this->helper->get_token_option_name( $this->service_name );

    if( get_option( $option_name ) ) {
      update_option( $option_name, serialize( $access_token ) );
    } else {
      add_option( $option_name, serialize( $access_token ) );
    }
  }

  /**
   * Get access_token from DB.
   * @return mixed|void
   */
  private function get_access_token() {
    $option_name = $this->helper->get_token_option_name( $this->service_name );
    return unserialize( get_option( $option_name ) );
  }

  /**
   * Refresh access token.
   * @return bool
   */
  private function refresh() {
    $token = $this->get_access_token();
    if( !isset( $token['refresh_token'] ) || !$token['refresh_token'] ) {
      return false;
    }

    $this->client->refreshToken( $token['refresh_token'] );
  }

  /**
   * Revoke all tokens.
   */
  private function reset() {
    $this->client->revokeToken();
    if( isset( $_SESSION['access_token'] ) )
      unset( $_SESSION['access_token'] );
  }

  /**
   * Return button to authorize plugin.
   *
   * @return string
   */
  private function get_authorize_button_html() {
    $authUrl = $this->client->createAuthUrl();
    return '<a class="button button-secondary" href="' . $authUrl . '">' . _( 'Authorize Plugin' ) . '</a>';
  }

}
