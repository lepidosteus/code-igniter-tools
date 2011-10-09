<?php
function get_instance()
{
  static $_has_initialized = false;

  if ($_has_initialized) {
    return CI_Controller::get_instance();
  }

  define('NO_CI_INIT', true);

  require(dirname(__FILE__)'/../../index.php'); // make sure this line points to where your index.php file is

  require(BASEPATH.'core/Common.php');

  if (defined('ENVIRONMENT') && file_exists(APPPATH.'config/'.ENVIRONMENT.'/constants.php')) {
    require(APPPATH.'config/'.ENVIRONMENT.'/constants.php');
  } else {
    require(APPPATH.'config/constants.php');
  }

  if (isset($assign_to_config['subclass_prefix']) && $assign_to_config['subclass_prefix'] != '') {
    get_config(array('subclass_prefix' => $assign_to_config['subclass_prefix']));
  }

  $CFG =& load_class('Config', 'core');

  if (isset($assign_to_config)) {
    $CFG->_assign_to_config($assign_to_config);
  }

  require BASEPATH.'core/Controller.php';

  if (file_exists(APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php')) {
    require APPPATH.'core/'.$CFG->config['subclass_prefix'].'Controller.php';
  }

  $class = $CFG->config['subclass_prefix'].'Controller';

  $_has_initialized = true;

  return new $class();
}
