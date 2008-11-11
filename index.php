<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de" xml:lang="de">
<head>
  <title>Mr. &amp; Mrs. Wahlen</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <!--[if IE]>
    <style type="text/css">
      #login fieldset {
        padding-left: 160px;
        width: 120px;
      }
      #login fieldset legend {
        margin: 0;
        margin-left: -70px;
      }
    </style>
  <![endif]-->
  <!--[if lt IE 6]>
    <style type="text/css">
      #login fieldset {
        width: 280px;
      }
      .message {
        width: 408px;
      }
    </style>
  <![endif]-->
  <script type="text/javascript">
    function check()
    {
      list = document.getElementsByTagName('select');
      list2 = new Array()
      for(i = 0; i < list.length; i++)
        if(list[i].value == '')
          list2.push(i)
      if(list2.length > 0)
        return confirm("Du hast nicht in jeder Kategorie gewählt, bist du sicher, dass du deine Stimme trotzdem abgeben willst?")
      return true
    }
  </script>
</head>
<body>
<?php
function make_list($list)
{
  $ret = '<option value=""></option>'."\n";
  foreach($list as $key => $value)
    $ret .= '<option value="'.$key.'">'.$value."</option>\n";
  return $ret;
}

function make_form($key = '', $false = false)
{
  $text = $false? '<legend>Ungültiger Key</legend>'."\n" : '';
  $class = $false? ' class="false"' : '';
  echo <<<EOH
<form action="{$_SERVER['PHP_SELF']}" method="post" id="login"$class>
<fieldset>
$text
<input type="text" name="key" value="{$key}" />
<input type="submit" value="OK" />
</fieldset>
</form>
EOH;
}


if(!empty($_POST['key']))
{
  // make MySQL connection
  require_once('mysql.inc.php');
  require_once('mysql.class.php');
  $prefix = 'mrmrs_';
  $sql = new MySQL("SELECT `used` FROM `{$prefix}keys` WHERE `key` = '%s'", $_POST['key']);
  if($sql->num != 1 || $sql->fetchSingle() != 0)
  {
    make_form($_POST['key'], true);
  }
  else
  {
    $sql = new MySQL("SELECT `id`, `gender`, `name` FROM `{$prefix}persons` ORDER BY `name`");
    $persons = array();
    while($row = $sql->fetchRow())
      $persons[$row['gender']][$row['id']] = $row['name'];
    if(empty($_POST['votes']))
    {
      $male_list = make_list($persons['m']);
      $female_list = make_list($persons['w']);
      // show choices
      $questions = new MySQL("SELECT `id`, `question` FROM `{$prefix}questions`");
      echo <<<EOH
  <form action="{$_SERVER['PHP_SELF']}" method="post" onsubmit="return check()">
  <table id="questions">
    <tr>
      <th class="question">Titel</th>
      <th class="mister">Mister</th>
      <th class="missis">Missis</th>
    </tr>
EOH;
      while($question = $questions->fetchRow())
      {
        echo <<<EOH
    <tr>
      <td class="question">{$question['question']}</td>
      <td class="mister"><select name="votes[{$question['id']}][m]">{$male_list}</select></td>
      <td class="missis"><select name="votes[{$question['id']}][w]">{$female_list}</select></td>
    </tr>
EOH;
      }
      echo <<<EOH
  </table>
  <input type="hidden" name="key" value="{$_POST['key']}" />
  <input type="submit" value="Absenden" />
  <input type="reset" value="Formular leeren" />
  </form>
EOH;
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
      echo '<div class="message">Erfolgreich abgestimmt!<br />Vielen Dank.</div>';
    }
  }
}
else
  make_form();
?>
</body>
</html>
