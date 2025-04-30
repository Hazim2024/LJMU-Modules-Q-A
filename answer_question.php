<?php
session_start();

// load questions and error handling here
$dataFile = __DIR__ . '/data/questions.json';
if (!file_exists($dataFile)) {
  die("Error: questions.json not found at $dataFile");
}
$qs = json_decode(file_get_contents($dataFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
  die("Error parsing questions.json: " . json_last_error_msg());
}

//finding the requested question by its ID in the questions list else terminate if not found.
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$q  = null;
foreach($qs as $x) {
  if (isset($x['id']) && $x['id'] === $id) {
    $q = $x;
    break;
  }
}
if (!$q) {
  die("Question #$id not found");
}

//Load all answers from the json file and filter answers related to that question ID found above
$ansFile = __DIR__ . '/data/answers.json';
if (!file_exists($ansFile)) {
  file_put_contents($ansFile, "[]");
}

$allAns = json_decode(file_get_contents($ansFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
  die("Error parsing answers.json: " . json_last_error_msg());
}

$answers = array_filter($allAns, fn($a)=>($a['question_id'] ?? 0) === $id);

$pageTitle = "Question: " . htmlspecialchars($q['title']);
include 'header.php'; 
?>
<button id="btnScrollToTop" > <ion-icon name="arrow-up" aria-hidden="true"></ion-icon></button>
<!-- this section dynamically displays the question details  -->
<!-- visibility of the form is based on the user's role ensuring only staff can answer questions while student can view the question and answers. -->
<main>
  <div class="container form-container">
    <h1>Question Details</h1>

    <div class="question-card mb-4">
      <div class="question-card-header">
        <span class="module-badge"><?=htmlspecialchars($q['module_name'] ?? $q['module_code'] ?? '-')?></span>
        <span class="status-badge <?=htmlspecialchars($q['status'])?>">
          <?=ucfirst(htmlspecialchars($q['status']))?>
        </span>
      </div>
      <div class="question-card-body">
        <h2 class="question-title"><?=htmlspecialchars($q['title'])?></h2>
        <p class="question-text"><?=nl2br(htmlspecialchars($q['question_text']))?></p>
        <small class="question-meta">
          Asked by <?=htmlspecialchars($q['username'])?> on <?=htmlspecialchars($q['created_at'])?>
        </small>
      </div>
    </div>

    <?php if ($q['status'] === 'answered'): ?>
      <div class="question-card answer-card" role="region" aria-labelledby="Answers-heading">
        <div class="question-card-headeri text-center" id="Answers-heading">
          Answer<?= count($answers)>1 ? 's' : '' ?>
        </div>
        <div class="question-card-body">
          <?php $i=0; foreach($answers as $a): ?>
            <blockquote class="blockquote">
              <p class="question-text"><?=nl2br(htmlspecialchars($a['answer_text']))?></p>
              <small class="question-meta">
                by <?=htmlspecialchars($a['username'])?> on <?=htmlspecialchars($a['created_at'])?>
              </small>
            </blockquote>
            <?php if (++$i < count($answers)): ?> 
              <hr class="answer-divider">
            <?php endif;?>
          <?php endforeach;?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'staff'): ?>
      <form action="app/answer_question.php" method="POST">
        <input type="hidden" name="question_id" value="<?=$id?>">
        <div class="form-group">
          <label for="answer_text">Your Answer</label>
          <textarea id="answer_text" name="answer_text" class="form-control" rows="5" aria-required="true" aria-label="Answer text" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Post Answer</button>
        <a href="view_question.php" class="btn btn-secondary">Back</a>
      </form>
    <?php else: ?>
      <div class="alert alert-info" role="alert">You must be staff to post an answer.</div>
      <button onclick="history.back()" class="button back-button-student">Back</button>
    <?php endif; ?>

  </div>
</main>
<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
