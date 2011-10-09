<?php
// an example model using the query_params() function
class example_pg_model extends CI_Model
{
  public function foobar()
  {
    // when the query gets, executed $1 and $2 will be replaced respectively by 'foo' and 'bar', se pg_query_params documentation for more info
    $result = $this->db->query_params("SELECT field FROM table WHERE something = $1 and something_else = $2", array('foo', 'bar'));
    // use $result as usual ...
  }
}
