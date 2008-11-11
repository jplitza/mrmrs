<?php
require_once('includes/smarty/Smarty.class.php');
require_once('includes/mysql.class.php');
date_default_timezone_set('Europe/Berlin');
mb_internal_encoding('UTF-8');
$t = new Smarty;
$prefix = 'mrmrs_';

if(!empty($_POST['key']))
{
  $sql = new MySQL("SELECT `used` FROM `{$prefix}keys` WHERE `key` = '%s'", $_POST['key']);
  if($sql->num != 1 || $sql->fetchSingle() != 0)
  {
    $t->assign('login_failed', true);
    $t->display('login.tpl');
  }
  else
  {
    $sql = new MySQL("SELECT `id`, `gender`, `name` FROM `{$prefix}persons` ORDER BY `name`");
    $persons = array();
    while($row = $sql->fetchRow())
      $persons[$row['gender']][$row['id']] = $row['name'];
    if(empty($_POST['votes']))
    {
      // show choices
      $questions = new MySQL("SELECT `id`, `question` FROM `{$prefix}questions`");
      $t->assign('questions', $questions->fetchRows());
      $t->assign('persons', $persons);
      $t->display('list.tpl');
    }
    else
    {
      // make choices
      $votes = array();
      $query = "INSERT INTO `{$prefix}answers` (`qid`, `gender`, `answer`, `key`) VALUES ";
      foreach($_POST['votes'] as $key => $val)
        if(ctype_digit($key) || is_int($key))
        {
          if(!empty($val['m']) && (ctype_digit($val['m']) || is_int($val['m'])))
          {
            if(!in_array($val['m'], array_keys($persons['m'])))
              die('Manipulationsversuch!');
            $votes[] = "('$key', 'm', '".$val['m']."', '".$_POST['key']."')";
          }
          if(!empty($val['w']))
          {
            if(!in_array($val['w'], array_keys($persons['w'])))
              die('Manipulationsversuch!');
            $votes[] = "('$key', 'w', '".$val['w']."', '".$_POST['key']."')";
          }
        }
      new MySQL("BEGIN");
      if(count($votes) > 0)
      {
        $query .= implode(', ', $votes);
        new MySQL($query);
      }
      new MySQL("UPDATE `{$prefix}keys` SET `used` = UNIX_TIMESTAMP() WHERE `key` = '%s'", $_POST['key']);
      new MySQL("COMMIT");
      $t->display('message.tpl');
    }
  }
}
else
  $t->display('login.tpl');
?>
