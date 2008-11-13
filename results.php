<?php
$questions_sql = new MySQL("SELECT * FROM `{$prefix}questions`");
$questions = array();
while($row = $questions_sql->fetchRow())
  $questions[$row['id']] = array('question' => $row['question']);
$answers_sql = new MySQL("SELECT `qid`, `answer`, `gender`, COUNT(*) AS 'count' FROM `{$prefix}answers` GROUP BY `qid`, `gender`, `answer` ORDER BY `count` DESC");
while($row = $answers_sql->fetchRow())
  $questions[$row['qid']]['answers'][$row['gender']][] = array('count' => $row['count'], 'key' => $row['answer']);
$persons_sql = new MySQL("SELECT `id`, `name` FROM `{$prefix}persons`");
$persons = array();
while($row = $persons_sql->fetchRow())
  $persons[$row['id']] = $row['name'];
$t->assign('questions', $questions);
$t->assign('persons', $persons);
if(!empty($_GET['export']) && $_GET['export'] == 'csv')
{
  header('Content-Type: text/csv; charset=UTF-8');
  $t->assign('export', $_GET['export']);
}
elseif(!empty($_GET['display']) && $_GET['display'] == 'all')
  $t->assign('all', true);
?>
