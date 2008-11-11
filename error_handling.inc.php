<?php
function gen_backtrace($bt)
{
  $backtrace = "";
  foreach($bt as $key => $step)
  {
    if($key == 0)
      continue;
    if(empty($step['class'])) $step['class'] = '';
    if(empty($step['object'])) $step['object'] = '';
    if(empty($step['type'])) $step['type'] = '';
    if(empty($step['args'])) $step['args'] = array();
    if(empty($step['file'])) $step['file'] = 'unknown';
    if(empty($step['line'])) $step['line'] = 0;
    // expand array args to print_r style and represent strings enclosed with ""
    $args = array_map(create_function('$val', 'return is_array($val) || is_object($val) ? preg_replace("/\s+/", " ", print_r($val, true)) : (is_string($val) ? \'"\'.$val.\'"\' : $val);'), $step['args']);
    // append line in style:
    // #n  (object->)function(args) called at [file:line]
    $backtrace .= sprintf("#%-2d %s%s%s(%s) called at [%s:%d]\n", $key, $step['class'], $step['type'], $step['function'], implode(', ', $args), $step['file'], $step['line']);
  }
  return trim($backtrace);
}

function error_handler($n, $str, $file, $line)
{
  static $levels = array(
    E_ERROR         => 'Error',
    E_WARNING       => 'Warning',
    E_NOTICE        => 'Notice',
    E_USER_ERROR    => 'User Error',
    E_USER_WARNING  => 'User Warning',
    E_USER_NOTICE   => 'User Notice',
    E_STRICT        => 'Strict Warning');

  $critical = E_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR;

  if($n & error_reporting())
  {
    $backtrace = htmlspecialchars(gen_backtrace(debug_backtrace()));
    echo <<<EOT
<div class="php-error">
<h5>PHP {$levels[$n]}</h5>
<dl>
  <dt>Fehler:</dt>
  <dd>{$str}</dd>
  <dt>Datei:</dt>
  <dd>{$file}</dd>
  <dt>Zeile:</dt>
  <dd>{$line}</dd>
  <dt>Backtrace:</dt>
  <dd><pre>{$backtrace}</pre></dd>
</dl>
</div>
EOT;
  }
  elseif($n & ($critical | E_WARNING | E_USER_WARNING))
  {
    if(!isset($backtrace)) $backtrace = gen_backtrace(debug_backtrace());
    $address = defined('ADMIN_MAIL')? (defined('ADMIN_NAME')? ADMIN_NAME.'<'.ADMIN_MAIL.'>' : ADMIN_MAIL) : 'admin@'.$_SERVER['SERVER_NAME'];
    mail($address, 'PHP Fehler in NPShare', sprintf("%s: %s in %s:%d.\nIP: %s\n\nBacktrace:\n%s", $levels[$n], $str, $file, $line, $_SERVER['REMOTE_ADDR'], $backtrace), "From: $address\r\nContent-Type: text/plain\r\nContent-Transfer-Encoding: 8bit\r\nX-Mailer: PHP/".phpversion());
  }
  if(!($n & error_reporting()) && $n & $critical)
  {
    ob_end_clean();
    header('Content-Encoding: none', true, 500);
    global $t;
    if(isset($t) && is_a($t, 'Smarty'))
      $t->display('error.tpl');
    else
      echo file_get_contents('data/templates/error.tpl');
  }
  if($n & $critical)
    die();
  return true;
}

function exception_handler($e)
{
  error_handler(E_ERROR, "Uncaught exception '".$e->getName()."'", $e->getFile(), $e->getLine());
}

// debugging is enabled in .htaccess by setting environment variable DEBUG=1
if((!empty($_ENV['DEBUG']) && $_ENV['DEBUG'] > 0) || (!empty($_SERVER['DEBUG']) && $_SERVER['DEBUG'] > 0))
{
  $errors = E_ALL;
  if(defined('E_STRICT'))
    $errors |= E_STRICT;
  if(defined('E_RECOVERABLE_ERROR'))
    $errors |= E_RECOVERABLE_ERROR;
  if(defined('E_DEPRECATED'))
    $errors |= E_DEPRECATED;
  error_reporting($errors);
  unset($errors);
  define('DEBUG', empty($_ENV['DEBUG'])? $_SERVER['DEBUG'] : $_ENV['DEBUG']);
}
else
{
  error_reporting(0);
  define('DEBUG', 0);
}

set_error_handler('error_handler');
set_exception_handler('exception_handler');
?>
