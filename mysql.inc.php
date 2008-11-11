<?php
class MySQLAccess {
  private $access = array(
    'server' => 'localhost',
    'username' => 'dead_orc',
    'password' => 'q354dknd'
  );
  protected $database = 'dead_orc_sql4';

  protected function connect()
  {
    return mysql_connect($this->access['server'], $this->access['username'], $this->access['password']);
  }
}
?>
