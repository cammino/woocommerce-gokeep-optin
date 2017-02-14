<?php
/**
 * Plugin Name: Gokeep Optin
 * Author: Gokeep
 * Author URI: http://gokeep.me
 * Version: 1.0.0
 * License: GPLv2 or later
 * Text Domain: gokeep-optin
 * Domain Path: languages/
 * Description: Integração com a modal optin do Gokeep.
 */


if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! class_exists( 'Gokeep_Optin' ) ):

// load includes.
require_once("includes/class-gokeep-optin-model.php");

class Gokeep_Optin
{

  /**
   * Instance of this class.
   *
   * @var object
   */
  protected static $instance = null;

  /**
   * Instance gokeep model.
   *
   * @var object
   */
  protected static $model = null;

  /**
   * Active plugin and verify dependencies.
   *
   *  @return void
   **/
  public function active_plugin() {
    
    if ( is_plugin_active('woocommerce-gokeep-integration/woocommerce-gokeep-integration.php') ) {
      
      // register unistall plugin
      register_uninstall_hook( __FILE__, array( 'Gokeep_Optin', 'unistall_plugin'), 0  );
      
      // create tables
      self::$model = new Gokeep_Optin_Model;
      self::$model->create_tables();

      // load instance plugin
      add_action( 'plugins_loaded', array( 'Gokeep_Optin', 'get_instance' ), 0 );
    
    } else {
      
      $class = 'notice notice-error';
      $message = __( 'Please active Woocommerce Gokeep extensions before activating', 'sample-text-domain' );
    
      printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
      @trigger_error(__('Please active Woocommerce Gokeep extensions before activating.', 'sample-text-domain'), E_USER_ERROR);
    }
  }
  
  /**
   * Method called if this plugin is unistall
   *
   * @return void
   **/
  public function unistall_plugin()
  {
    self::$model = new Gokeep_Optin_Model;
    self::$model->delete_tables();
  }

  /**
   * Return an instance of this class.
   *
   * @return object A single instance of this class.
   */
  public static function get_instance() {
    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  /**
   * load views for admin
   *
   * @return html
   **/
  public function load_views()
  {
    $page = $_GET['page'];
    self::$model = new Gokeep_Optin_Model;

    if ($page == "list-emails") {
      $optins = self::$model->get_all_optins();
      $optins_columns = self::$model->get_column_optins();
    }

    include "views/{$page}.php";
  }

  /**
   * create items for menu
   *
   * @return void
   **/
  public function gokeep_optin_menu()
  {
    add_menu_page( __("Gokeep Optin"), __("Gokeep Optin"), "manage_options", "list-emails", array( "Gokeep_Optin", "load_views" ), plugins_url( 'gokeep-optin/gokeep-logo.png' ));

  }


  public function post_save_optin()
  {

    $data_save = array(
      'name'  => $_POST['gokeep_name'],
      'email' => $_POST['gokeep_email']
    );

    if ($data_save['name'] && $data_save['email']) {
      self::$model = new Gokeep_Optin_Model;
      $isOptin = self::$model->check_optin($data_save['email']);
      
      $subject = 'Cupom de desconto';
      $body    = file_get_contents(plugins_url("gokeep-optin/views/email.php?name={$data_save['name']}"));
      
      $headers = array('Content-Type: text/html; charset=UTF-8');
      wp_mail( $data_save['email'], $subject, $body, $headers );

      if (!$isOptin)
        self::$model->save_optin($data_save);

      echo json_encode(array('success' => true));
    } else {
      echo json_encode(array('error' => true));
    }

    die();
  }

}

register_activation_hook( __FILE__, array( 'Gokeep_Optin', 'active_plugin'), 0 );

// register Menu
add_action( 'admin_menu', array( 'Gokeep_Optin', 'gokeep_optin_menu' ), 0 );

// add ajax
add_action( 'wp_ajax_nopriv_post_save_optin', array( 'Gokeep_Optin', 'post_save_optin' ) );
endif;