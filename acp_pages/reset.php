<?php
if(!empty($_POST['confirmation']) && md5($_POST['confirmation']) == 'f552984fa479037d44900953148aaf74')
{
  new MySQL("TRUNCATE TABLE `{$prefix}keys`");
  new MySQL("TRUNCATE TABLE `{$prefix}answers`");
  new MySQL("DELETE FROM `{$prefix}persons` WHERE `gender` IS NULL");
  $t->assign('deleted', true);
}
?>
