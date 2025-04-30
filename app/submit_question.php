<?php
session_start();

// for student only 
if (!isset($_SESSION['username']) || $_SESSION['role']!=='student') {
  $_SESSION['error']="You must be logged in as a student to post.";
  header("Location: ../submit_question.php");
  exit;
}

if (empty($_POST['module']) || empty($_POST['title']) || !trim($_POST['question'])) {
  $_SESSION['error']="Please complete all fields.";
  header("Location: ../submit_question.php");
  exit;
}

// Profanity filter for FR that is reading banned words from a file and scanning the question text before appending it to the question.JSON file.
$bannedFile = __DIR__ . "/../data/banned_words.txt";
$banned = [];
if (file_exists($bannedFile)) {
  foreach (file($bannedFile, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#')) {
      continue;
    }
    $banned[] = $line;
  }
}
// combine title and question for scanning
$hay = mb_strtolower($_POST['title'] . " " . $_POST['question'], 'UTF-8');
foreach ($banned as $bad) {
  if ($bad === '') continue;
  // using case-insensitive search
  if (mb_stripos($hay, mb_strtolower($bad,'UTF-8'), 0, 'UTF-8') !== false) {
    $_SESSION['error'] = "Your question contains a forbidden word (“{$bad}”).";
    header("Location: ../submit_question.php");
    exit;
  }
}
// ──────────────────────────────────────────────────────────────────────────────


// here we need to find the modules student has chosen for name and tutor so loading question.json and modules.json file 
$dataFile = __DIR__ . "/../data/questions.json";
$all = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true)
     : [];

$maxId = 0;
foreach ($all as $q) {
  $maxId = max($maxId, $q['id'] ?? 0);
}
$newId = $maxId + 1;

$mods = json_decode(
  file_get_contents(__DIR__ . "/../data/modules.json"),
  true
)['modules'];

// find the one the student chose
$code = $_POST['module'];
$name = $tutor = '';
foreach ($mods as $m) {
  if ($m['code'] === $code) {
    $name  = $m['name'];
    $tutor = $m['tutor'];
    break;
  }
}

//building new question record
$new = [
  'id'            => $newId,
  'username'      => $_SESSION['username'],
  'module_code'   => $code,
  'module_name'   => $name,
  'module_tutor'  => $tutor,
  'title'         => $_POST['title'],
  'question_text' => trim($_POST['question']),
  'status'        => 'unanswered',
  'votes'         => 0,
  'created_at'    => date('Y-m-d H:i:s')
];

$all[] = $new;
file_put_contents($dataFile, json_encode($all, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));

// act as a flash that will be shown when submitted and newID is used to redirect the user to the question page
$_SESSION['success']  = "Question submitted!";
$_SESSION['last_qid'] = $newId;

header("Location: ../submit_question.php");
exit;
