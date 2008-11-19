<?php
if(!empty($_GET['new']) && ctype_digit($_GET['new']))
{
  $num = $_GET['new'];
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
}
elseif(!empty($_GET['invalidate']))
{
  new MySQL("UPDATE `{$prefix}keys` SET `used` = UNIX_TIMESTAMP() WHERE `key` = '%s' LIMIT 1", $_GET['invalidate']);
}
elseif(!empty($_GET['delete']))
{
  new MySQL("BEGIN");
  new MySQL("DELETE FROM `{$prefix}answers` WHERE `key` = '%s'", $_GET['delete']);
  new MySQL("UPDATE `{$prefix}keys` SET `used` = NULL WHERE `key` = '%s' LIMIT 1", $_GET['delete']);
  new MySQL("COMMIT");
}
$counts = new MySQL("SELECT COUNT(*) FROM `{$prefix}keys` WHERE `used` IS NOT NULL");
$sql = new MySQL("SELECT `key`, `used` FROM `{$prefix}keys`");
$t->assign('keys', $sql->fetchRows());
$t->assign('counts', array('total' => $sql->num, 'used' => $counts->fetchSingle()));
?>
