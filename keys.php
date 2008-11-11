<?php
$sql = new MySQL("SELECT `key`, `used` FROM `{$prefix}keys`");
$t->assign('keys', $sql->fetchRows());
?>
