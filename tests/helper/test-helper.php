<?php

require __DIR__ . '/../../app/helper.php';

class HelperTest extends WP_UnitTestCase {

  public function setup() {
    $this->helper = new WpApiAuth_Helper();
  }

  public function test_get_token_option_name() {
    $this->assertEquals(
      $this->helper->get_token_option_name( 'google' ),
      'wpapi_token_google'
    );
  }

  public function test__() {
    $this->assertEquals(
      $this->helper->_( 'hoge' ),
      'hoge'
    );
  }

  public function test_e() {
    $this->expectOutputString('hoge');
    $this->helper->e('hoge');
  }
}

