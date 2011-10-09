<?php
// an example model using the additionnal mysql driver functions
class example_mysql_model extends CI_Model
{
  public function add_or_replace()
  {
    // "REPLACE INTO table (foo) VALUES (bar)"
    $this->insert_replace('table', array('foo' => 'bar'));

    // you can also do
    $this->replace();
    $this->insert('table', array('foo' => 'bar'));
  }

  public function add_or_ignore()
  {
    // "INSERT IGNORE INTO table (foo) VALUES (bar)"
    $this->insert_ignore('table', array('foo' => 'bar'));

    // you can also do
    $this->ignore();
    $this->insert('table', array('foo' => 'bar'));
  }

  public function update_or_ignore()
  {
    // "UPDATE IGNORE table SET foo = bar WHERE hallo = world"
    $this->update_ignore('table', array('foo' => 'bar'), array('hello' => 'world'));

    // you can also do
    $this->ignore();
    $this->update('table', array('foo' => 'bar'), array('hello' => 'world'));
  }

  public function add_or_update()
  {
    // "INSERT INTO table (foo) VALUES (bar) ON DUPLICATE KEY UPDATE foo = foo + 1"
    $this->on_duplicate_key('foo = foo + 1');
    $this->insert('table', array('foo' => 'bar'));
  }
}
