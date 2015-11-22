<?php

// Don't allow plugin to be loaded directory
if ( !defined( 'ABSPATH' ) )
  exit();

// Include Admin Class
require_once( WP_API_AUTH_DIR . 'app/admin.php' );
require_once( WP_API_AUTH_DIR . 'app/helper.php' );
$helper = new WpApiAuth_Helper();
?>

<div class="wrap">
  <h2><?php $helper->e( 'WP API Settings' ); ?></h2>
  <?php WpApiAuth_Admin::get_template( 'google' ); ?>
</div>
