<?php
/**
 * Custom mysql driver extension
 *
 * Add the following functions:
 *  - ignore(), insert_ignore(), update_ignore()
 *  - replace(), insert_replace()
 *  - on_duplicate_key()
 */
class MY_DB_mysql_driver extends CI_DB_mysql_driver
{
  protected
    $ar_ignore = false,
    $ar_replace = false,
    $ar_on_duplicate_key = false;

  function ignore($val = true)
  {
    $this->ar_ignore = (is_bool($val)) ? $val : true;
    return $this;
  }

  function insert_ignore($table = '', $set = NULL)
  {
    $this->ignore(true);
    parent::insert($table, $set);
  }

  function update_ignore($table = '', $set = NULL, $where = NULL, $limit = NULL)
  {
    $this->ignore(true);
    parent::update($table, $set, $where, $limit);
  }

  function replace($val = true)
  {
    $this->ar_replace = (is_bool($val)) ? $val : true;
    return $this;
  }

  function insert_replace($table = '', $set = NULL)
  {
    $this->replace(true);
    parent::insert($table, $set);
  }

  function on_duplicate_key($val = false)
  {
    $this->ar_on_duplicate_key = (is_string($val) && !empty($val)) ? " ON DUPLICATE KEY UPDATE ".$val : false;
    return $this;
  }

  function _reset_select()
  {
    $ar_reset_items = array(
      'ar_ignore' => false,
      'ar_replace' => false,
      'ar_on_duplicate_key' => false
    );
    $this->_reset_run($ar_reset_items);

    parent::_reset_select();
  }

  function _reset_write()
  {
    $ar_reset_items = array(
      'ar_ignore' => false,
      'ar_replace' => false,
      'ar_on_duplicate_key' => false
    );
    $this->_reset_run($ar_reset_items);

    parent::_reset_write();
  }

  function _insert($table, $keys, $values) {
    if ($this->ar_replace) {
      $sql = 'REPLACE';
    } else {
      $sql = 'INSERT';
      if ($this->ar_ignore) {
        $sql .= ' IGNORE';
      }
    }
    $sql = "$sql INTO ".$table." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")";
    if ($this->ar_on_duplicate_key) {
      $sql .= $this->ar_on_duplicate_key;
    }
    return $sql;
  }

  function _update($table, $values, $where, $orderby = array(), $limit = FALSE)
  {
    foreach($values as $key => $val) {
      $valstr[] = $key." = ".$val;
    }

    $limit = (!$limit) ? '' : ' LIMIT '.$limit;

    $orderby = (count($orderby) >= 1)?' ORDER BY '.implode(", ", $orderby):'';

    if ($this->ar_ignore) {
      $sql = "UPDATE IGNORE ";
    } else {
      $sql = "UPDATE ";
    }

    $sql .= $table." SET ".implode(', ', $valstr);

    $sql .= ($where != '' AND count($where) >=1) ? " WHERE ".implode(" ", $where) : '';

    $sql .= $orderby.$limit;

    return $sql;
  }
}
