<?php
/**
 * Custom Loader allowing overloading of database drivers
 *
 * Driver has to be in core/, ex: application/core/MY_DB_postgre_driver.php
 * and "class MY_DB_postgre_driver extends CI_DB_postgre_driver"
 */
class MY_Loader extends CI_Loader
{
  public function database($params = '', $return = FALSE, $active_record = NULL)
  {
    // Grab the super object
    $CI =& get_instance();

    // Do we even need to load the database class?
    if (class_exists('CI_DB') AND $return == FALSE AND $active_record == NULL AND isset($CI->db) AND is_object($CI->db))
    {
      return FALSE;
    }

    require_once(BASEPATH.'database/DB.php');

    // MODIFIED

    $db = DB($params, $active_record);

    $custom_db_driver = config_item('subclass_prefix').'DB_'.$db->dbdriver.'_driver';
    $custom_db_driver_file = APPPATH.'core/'.$custom_db_driver.EXT;

    if (file_exists($custom_db_driver_file)) {
      require_once($custom_db_driver_file);
      $db = new $custom_db_driver(get_object_vars($db));
    }

    // !MODIFIED

    if ($return === TRUE)
    {
      return $db; // MODIFIED
    }

    // Initialize the db variable.  Needed to prevent
    // reference errors with some configurations
    $CI->db = '';

    // Load the DB class
    $CI->db =& $db; // MODIFIED
  }
}
