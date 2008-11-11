<?php
$questions_sql = new MySQL("SELECT * FROM `{$prefix}questions`");
$questions = array();
while($row = $questions_sql->fetchRow())
  $questions[$row['id']] = array('question' => $row['question']);
$answers_sql = new MySQL("SELECT `qid`, `answer`, COUNT(*) AS 'count' FROM `{$prefix}answers` GROUP BY `qid`, `answer`");
while($row = $answers_sql->fetchRow())
  $questions[$row['qid']]['answers'][$row['answer']] = array('count' => $row['count'], 'key' => $row['answer']);
$persons_sql = new MySQL("SELECT `id`, `name` FROM `{$prefix}persons`");
$persons = array();
while($row = $persons_sql->fetchRow())
  $persons[$row['id']] = $row['name'];
$t->assign('questions', $questions);
$t->assign('persons', $persons);
?>
