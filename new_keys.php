<?php
$num = !empty($_GET['num']) && ctype_digit($_GET['num']) ? $_GET['num'] : 10;
require_once('mysql.class.php');
$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
$keys = array();
while($num-- > 0)
{
  mt_srand();
  $key = '';
  for($i = 8; $i > 0; $i--)
    $key .= substr($charset, mt_rand(0, strlen($charset)-1), 1);
  $keys[] = $key;
}
new MySQL("INSERT INTO `mrmrs_keys` (`key`) VALUES ('%s')", implode("'), ('", $keys), false);
?>
