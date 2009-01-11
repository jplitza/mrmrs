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
    $sql = new MySQL("SELECT `id`, `category`, `gender`, `name` FROM `{$prefix}persons` ORDER BY `name`");
    $persons = array();
    while($row = $sql->fetchRow())
      $persons[$row['category']][$row['gender']][$row['id']] = $row['name'];
    if(empty($_POST['votes']))
    {
      // show choices
      $sql = new MySQL("SELECT `id`, `category`, `question` FROM `{$prefix}questions`");
      $questions = array();
      while($row = $sql->fetchRow())
        $questions[$row['category']][] = $row;
      $yesid = new MySQL("SELECT `id` FROM `{$prefix}persons` WHERE `category` IS NULL AND `name` = 'yes'");
      $t->assign('questions', $questions);
      $t->assign('persons', $persons);
      $t->assign('yesid', $yesid->fetchSingle());
      $t->display('list.tpl');
    }
    else
    {
      $sql = new MySQL("SELECT `id`, `category`, `question` FROM `{$prefix}questions`");
      $questions = array();
      while($row = $sql->fetchRow())
        $questions[$row['id']] = $row;

      // make choices
      $votes = array();
      $query = "INSERT INTO `{$prefix}answers` (`qid`, `gender`, `answer`, `key`) VALUES ";
      foreach($_POST['votes'] as $key => $val)
        if(ctype_digit($key) || is_int($key))
        {
          if(!empty($val['b']))
          {
            $votes[] = "('$key', NULL, 1, '".$_POST['key']."')";
          }
          elseif(!empty($val['s']))
          {
            $tmpsql = new MySQL("SELECT `id` FROM `{$prefix}persons` WHERE `category` = 2 AND `name` = '%s'", $val['s']);
            if($tmpsql->num == 0)
            {
              $tmpsql = new MySQL("INSERT INTO `{$prefix}persons` (`category`, `gender`, `name`) VALUES (2, NULL, '%s')", $val['s']);
              $nid = $tmpsql->iid;
            }
            else
              $nid = $tmpsql->fetchSingle();
            $votes[] = "('$key', NULL, $nid, '".$_POST['key']."')";
          }
          else
          {
            if(!empty($val['m']) && (ctype_digit($val['m']) || is_int($val['m'])))
            {
              if(!in_array($val['m'], array_keys($persons[$questions[$key]['category']]['m'])))
                die('Manipulationsversuch!');
              $votes[] = "('$key', 'm', '".$val['m']."', '".$_POST['key']."')";
            }
            if(!empty($val['w']) && (ctype_digit($val['w']) || is_int($val['w'])))
            {
              if(!in_array($val['w'], array_keys($persons[$questions[$key]['category']]['w'])))
                die('Manipulationsversuch!');
              $votes[] = "('$key', 'w', '".$val['w']."', '".$_POST['key']."')";
            }
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
