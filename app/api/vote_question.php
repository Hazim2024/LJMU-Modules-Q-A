<?php
header('Content-Type: application/json');
session_start();


// making sure that only students may vote
if (empty($_SESSION['username']) || $_SESSION['role'] !== 'student') {
  http_response_code(403);
  exit;
}

$dataFile = __DIR__ . '/../../data/questions.json';
$all = json_decode(file_get_contents($dataFile), true);

$qid = (int)($_POST['id'] ?? 0);
$user = $_SESSION['username'];
$voted = false;

$votesFile = __DIR__ . '/../../data/question_votes.json';
$votesAll = file_exists($votesFile) ? json_decode(file_get_contents($votesFile), true)  : [];

// finding stored votes and removing the vote if it exists or adding it if it doesn't
if (!isset($votesAll[$qid])) $votesAll[$qid] = [];
if (in_array($user, $votesAll[$qid], true)) {
  $votesAll[$qid] = array_diff($votesAll[$qid], [$user]);
  $voted = false;
} else {
  $votesAll[$qid][] = $user;
  $voted = true;
}
file_put_contents($votesFile, json_encode($votesAll, JSON_PRETTY_PRINT));

// update question vote count
foreach ($all as &$q) {
  if ($q['id']===$qid) {
    $q['votes'] = count($votesAll[$qid]);
    break;
  }
}
file_put_contents($dataFile, json_encode($all, JSON_PRETTY_PRINT));

echo json_encode(['success'=>true,'votes'=> count($votesAll[$qid]),'voted'=> $voted]);
