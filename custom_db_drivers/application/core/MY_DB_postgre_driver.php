<?php
/**
 * Custom postgresql driver extension
 *
 * Add a query_params() function which pass through pg_query_params() (while query() uses pg_query)
 * Also does some basic convertion of php types into pg types on the fly when calling query_params()
 */
class MY_DB_postgre_driver extends CI_DB_postgre_driver
{
  protected
    $_tmp_query_params = array();

  public
    // accessed by the profiler class
    $query_params = array();

  public function query_params($sql, $params)
  {
    // logs the parameters if needed
    if ($this->save_queries == TRUE) {
      $this->query_params[$this->query_count] = $params;
    }

    // convert php types when needed (avoid having to convert everything application side everytime)
    $params = $this->convert_params($params);

    // save it for now
    $this->_tmp_query_params = $params;
    return parent::query($sql, false, true);
  }

  public function convert_params(array $params, $add_quote = false)
  {
    foreach ($params as $key => $value) {
      if (is_bool($value)) {
        $params[$key] = $value ? 'true' : 'false';
      } elseif (is_null($value)) {
        $params[$key] = 'NULL';
      } elseif (is_array($params[$key])) {
        $params[$key] = "{".implode(', ', $this->convert_params($params[$key], true))."}";
      } elseif (is_string($params[$key]) && $add_quote) {
        $params[$key] = '"'.$params[$key].'"';
      }
    }
    return $params;
  }

  public function _execute($sql)
  {
    $sql = $this->_prep_query($sql);
    if (!empty($this->_tmp_query_params)) {
      // we had some params saved up, use them and clear the list
      $result = @pg_query_params($this->conn_id, $sql, $this->_tmp_query_params);
      $this->_tmp_query_params = array();
      return $result;
    }
    return @pg_query($this->conn_id, $sql);
  }
}
