<?php

// Don't allow plugin to be loaded directory
if ( !defined( 'ABSPATH' ) )
  exit();

// Include Google Class
require_once( WP_API_AUTH_DIR . 'app/services/google.php' );
$google = new WpApiAuth_Google();
?>

<h3><?php _e( 'Google Analytics' ); ?></h3>
<form action="" method="post">
  <?php echo $google->get_admin_page_html(); ?>
</form>
