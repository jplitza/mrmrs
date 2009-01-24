<?php
$questions_sql = new MySQL("SELECT * FROM `{$prefix}questions`");
$questions = array();
while($row = $questions_sql->fetchRow())
  $questions[$row['category']][$row['id']] = array('question' => $row['question']);

$answers_sql = new MySQL("SELECT `qid`, `answer`, `gender`, COUNT(*) AS 'count', q.`category` FROM `{$prefix}answers` LEFT JOIN `{$prefix}questions` AS q ON `qid` = q.`id` GROUP BY `qid`, `gender`, `answer` ORDER BY q.`question` ASC, `gender` ASC, `count` DESC");
$old = array('count' => 0, 'qid' => 0, 'gender' => 0);
while($row = $answers_sql->fetchRow())
{
  if(!empty($row['gender']))
  {
    if($row['qid'] != $old['qid'] || $row['gender'] != $old['gender'])
      $i = 1;
    elseif($row['count'] < $old['count'])
      $i++;
    $old = $row;
    if(!isset($questions[$row['category']][$row['qid']]['answers'][$row['gender']][$i]))
      $questions[$row['category']][$row['qid']]['answers'][$row['gender']][$i] = array();
    $questions[$row['category']][$row['qid']]['answers'][$row['gender']][$i][] = array('count' => $row['count'], 'key' => $row['answer']);
  }
  else
    $questions[$row['category']][$row['qid']]['answers'][] = array('count' => $row['count'], 'key' => $row['answer']);
}

$counts_sql = new MySQL("SELECT `gender`, `qid`, COUNT(*) AS 'count' FROM `{$prefix}answers` GROUP BY `qid`, `gender`");
$counts = array();
while($row = $counts_sql->fetchRow())
  $counts[$row['qid']][$row['gender']] = $row['count'];

$persons_sql = new MySQL("SELECT `id`, `name` FROM `{$prefix}persons`");
$persons = array();
while($row = $persons_sql->fetchRow())
  $persons[$row['id']] = $row['name'];
$votes_sql = new MySQL("SELECT COUNT(*) FROM `{$prefix}keys` WHERE `used` IS NOT NULL");

$t->assign('questions', $questions);
$t->assign('persons', $persons);
$t->assign('votes', $votes_sql->fetchSingle());
$t->assign('counts', $counts);

if(!empty($_GET['export']) && $_GET['export'] == 'csv')
{
  header('Content-Type: text/csv; charset=UTF-8');
  $t->assign('export', $_GET['export']);
}
elseif(!empty($_GET['display']) && $_GET['display'] == 'all')
  $t->assign('all', true);
?>
