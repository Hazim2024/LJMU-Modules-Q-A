<?php
header('Content-Type: application/json');
session_start();

$dataFile = __DIR__ . '/../../data/questions.json';
$all = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

//makig sure each user can see their own votes highleighted in yellow when they are logged in

$votesFile = __DIR__ . '/../../data/question_votes.json';
$allVotes  = file_exists($votesFile) ? json_decode(file_get_contents($votesFile), true) : [];

$user = $_SESSION['username'] ?? '';

foreach ($all as &$q) {
  $qid = $q['id'];
  $voters = $allVotes[$qid] ?? [];
  $q['voted'] = in_array($user, $voters, true);
}


// reading filter/search for using in the filtering of the questions
$module = trim($_GET['module'] ?? '');              
$filter = $_GET['filter'] ?? 'all';
$search = trim($_GET['search'] ?? '');
$username  = $_SESSION['username'] ?? '';
$userRole  = $_SESSION['role'] ?? '';

// Loading the modules file to determine the modules for the staff
$modulesFile = __DIR__ . '/../../data/modules.json';
$allModules  = file_exists($modulesFile) ? json_decode(file_get_contents($modulesFile), true)['modules'] : [];

$userModules = [];
if ($userRole === 'staff' && $username) {
    foreach ($allModules as $m) {
        if ($m['tutor'] === $username) {
            $userModules[] = $m['code'];
        }
    }
}

// status filter
$all = array_filter($all, function($q) use($filter){
  if ($filter==='unanswered') return $q['status']==='unanswered';
  if ($filter==='answered')   return $q['status']==='answered';
  return true;
});

// 2) Filtering by module
if ($module !== '') {
  $all = array_filter($all, fn($q) => ($q['module_code'] ?? $q['module']) === $module);
} elseif (!empty($userModules)) {
  $all = array_filter($all, fn($q) => in_array($q['module_code'] ?? $q['module'], $userModules)); //filter by all modules assigned to this staff by reading the modules.json file
}

// search filter
if ($search!=='') {
  $s = mb_strtolower($search);
  $all = array_filter($all, function($q) use($s){
    return mb_stripos($q['title'], $s)!==false || mb_stripos($q['question_text'], $s)!==false;
  });
}

// 3) sorting by votes first and then created as mentioned in FRs 
usort($all, function($a,$b){
  if ($a['votes']!==$b['votes']) {
    return $b['votes'] - $a['votes'];
  }
  return strcmp($b['created_at'], $a['created_at']);
});

echo json_encode(array_values($all));
