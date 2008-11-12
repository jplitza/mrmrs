<?php
if(!empty($_GET['delete']))
{
  new MySQL("BEGIN");
  new MySQL("DELETE FROM `{$prefix}answers` WHERE `key` = '%s'", $_GET['delete']);
  new MySQL("UPDATE `{$prefix}keys` SET `used` = NULL WHERE `key` = '%s'", $_GET['delete']);
  new MySQL("COMMIT");
}
$sql = new MySQL("SELECT `key`, `used` FROM `{$prefix}keys`");
$t->assign('keys', $sql->fetchRows());
?>
