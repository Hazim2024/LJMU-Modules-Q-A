<?php
session_start();

if (empty($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
  header('Location: ../main.php');
  exit;
}

// validating using POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST'
    || empty($_POST['question_id'])
    || empty(trim($_POST['answer_text']))) {
  $_SESSION['error'] = "Please provide an answer.";
  header("Location: ../answer_question.php?id=" . (int)$_POST['question_id']);
  exit;
}

$qid  = (int)$_POST['question_id'];
$ans  = trim($_POST['answer_text']);
$user = $_SESSION['username'];
$now  = date('Y-m-d H:i:s');

//Appending to answers.json file 
$ansFile = __DIR__ . '/../data/answers.json';
$allAns  = file_exists($ansFile) ? json_decode(file_get_contents($ansFile), true)
         : [];
$nextId = (count($allAns) ? max(array_column($allAns,'id')) : 0) + 1;
$allAns[] = [
  'id'           => $nextId,
  'question_id'  => $qid,
  'username'     => $user,
  'answer_text'  => $ans,
  'created_at'   => $now
];
file_put_contents($ansFile, json_encode($allAns, JSON_PRETTY_PRINT));

//Mark question answered in questions.json 
$qsFile = __DIR__ . '/../data/questions.json';
$qs= json_decode(file_get_contents($qsFile), true);
foreach($qs as &$q) {
  if (($q['id'] ?? null) === $qid) {
    $q['status'] = 'answered';
    break;
  }
}
file_put_contents($qsFile, json_encode($qs, JSON_PRETTY_PRINT));

//redirecting staff back to the same page
header("Location: ../answer_question.php?id={$qid}");
exit;
