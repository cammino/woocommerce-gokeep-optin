<?php
/**
* Model of Gokeep Option
*/
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
class Gokeep_Optin_Model
{

  /**
   * Create tables on active plugin.
   *
   * @return void
   **/
  public function create_tables()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . "gokeep_optin";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      name varchar(255) NOT NULL,
      email varchar(255) NOT NULL,
      created_at timestamp DEFAULT NOW() NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    dbDelta( $sql );
  }


  /**
   * Delete tables on delete plugin.
   *
   * @return void
   **/
  public function delete_tables()
  {
    global $wpdb;

    $table_name = $wpdb->prefix . "gokeep_optin";

    $sql = "DROP TABLE {$table_name}";
    dbDelta( $sql );
  }

  /**
   * Get all registers in gokeep_optin
   *
   * @return array
   **/
  public function get_all_optins()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . "gokeep_optin";

    $results = $wpdb->get_results( "SELECT * FROM {$table_name}", OBJECT );

    return $results;
  }

  /**
   * Get all registers in gokeep_optin
   *
   * @return array
   **/
  public function get_column_optins()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . "gokeep_optin";

    return $wpdb->get_col( "DESC {$table_name}", 0 );
  }

  /**
   * Register optin
   *
   * @param array
   * @return boolean
   **/
  public function save_optin( $data = array() )
  {
    global $wpdb;
    $table_name = $wpdb->prefix . "gokeep_optin";

    $wpdb->insert(  $table_name,  array( 'name' => $data['name'],  'email' => $data['email'] ) );
  
    return true;
  }

  /**
   * Check optin
   *
   * @param string
   * @return boolean
   **/
  public function check_optin( $email = "" )
  {

    if (!$email)
      return false;

    global $wpdb;
    $table_name = $wpdb->prefix . "gokeep_optin";

    $results = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE email = '{$email}'", OBJECT );
      
    if ($results)
      return true;
    
    return false;
  }

}