<?php
require_once(dirname(__FILE__) . '/mysql.inc.php');
require_once(dirname(__FILE__) . '/error_handling.inc.php');

class MySQLException extends Exception
{
  function __construct($query)
  {
    parent::__construct();
    $backtrace = gen_backtrace(debug_backtrace());
    if(error_reporting() & E_ERROR)
    {
      echo <<<EOT
<div class="mysql-error">
<h5>MySQL-Fehler</h5>
<dl>
  <dt>Fehler:</dt>
  <dd>{$query->error[1]} ({$query->error[0]})</dd>
  <dt>Query:</dt>
  <dd><code>{$query->query}</code></dd>
  <dt>Backtrace:</dt>
  <dd><pre>{$backtrace}</pre></dd>
</dl>
</div>
EOT;
    }
    else
    {
      $address = defined('ADMIN_MAIL')? (defined('ADMIN_NAME')? ADMIN_NAME.'<'.ADMIN_MAIL.'>' : ADMIN_MAIL) : 'admin@'.$_SERVER['SERVER_NAME'];
      mail($address, 'MySQL Fehler in NPShare', 
        sprintf("Fehler:\n\t%s (%d)\nQuery:\n\t%s\nBacktrace:\n\t%s",
          $query->error[1], $query->error[0], $query->query, str_replace($backtrace, "\n", "\n\t")),
        "From: $address\r\nContent-Type: text/plain\r\nContent-Transfer-Encoding: 8bit\r\nX-Mailer: PHP/".phpversion());
    }
  }

  function getName()
  {
    return __CLASS__;
  }
}

class MySQL extends MySQLAccess
{
  private static $connection;
  public $query;
  public $result;
  public $error;
  public $num;
  public $type;

  public function __destruct()
  {
    if(isset($this->result) && !is_bool($this->result))
      mysql_free_result($this->result);
  }

  public function __construct($query)
  {
    if(func_num_args() == 0)
    {
      // create dummy object
      $this->num = 0;
      return;
    }
    if(!self::$connection)
    {
      self::$connection = $this->connect();
      if(!self::$connection)
        die();
      if(!mysql_select_db($this->database, self::$connection))
        $this->error();
      if(function_exists('mysql_set_charset'))
        mysql_set_charset('utf8', self::$connection);
      else      {
        mysql_query("SET NAMES 'utf8'");
        mysql_query("SET CHARACTER SET 'utf8'");
      }
      mysql_query("SET SQL_MODE = 'STRICT_ALL_TABLES'");
    }

    if(func_num_args() > 1)
    {
      $args = func_get_args();
      if($args[count($args)-1] !== false)
        $args = array_map(create_function('$val, $key', 'return $key == 0 ? $val : mysql_real_escape_string($val);'), $args, array_keys($args));
      $this->query = call_user_func_array('sprintf', $args);
    } else
      $this->query = $query;
    $this->result = mysql_query($this->query, self::$connection);
    $this->type = strtoupper(substr($this->query, 0, strpos($this->query, ' ')));

    if(mysql_errno(self::$connection) != 0)
      $this->error();

    if(!is_bool($this->result))
    {
      if($this->type == 'SELECT')
        $this->num = mysql_num_rows($this->result);
      elseif($this->type == 'INSERT' || $this->type == 'UPDATE' || $this->type == 'DELETE')
        $this->num = mysql_affected_rows(self::$connection);
      else
        $this->num = 0;
    }

    if(DEBUG == 2 && $this->type == 'SELECT')
      $this->test_keys();
  }
  private function test_keys()
  {
    $explain = new MySQL("EXPLAIN ".$this->query);
    while($explanation = $explain->fetchRow())
      if(!isset($explanation['key']))
      {
        trigger_error('MySQL: No suitable key found for query: '.$this->query, E_USER_NOTICE);
        break;
      }
    unset($explain, $explanation);
  }

  private function error()
  {
    $this->error = array(mysql_errno(self::$connection), mysql_error(self::$connection));
    throw new MySQLException($this);
  }

  public function fetchRow()
  {
    return mysql_fetch_assoc($this->result);
  }

  public function fetchRows()
  {
    $ret = array();
    while($ret[] = $this->fetchRow());
    unset($ret[count($ret)-1]);
    return $ret;
  }

  public function fetchSingle()
  {
    return mysql_result($this->result, 0, 0);
  }
}
?>
