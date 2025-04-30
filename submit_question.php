<?php
session_start();

// check user is student
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    $error = "You need to be a student to use this feature.";
}

// load modules list from data/modules.json for populating the dropdown menu
$modulesPath = __DIR__ . '/data/modules.json';
$modulesData = file_exists($modulesPath)
             ? json_decode(file_get_contents($modulesPath), true)
             : ['modules'=>[]];
$modules     = $modulesData['modules'];

$pageTitle = "Submit Question";
include 'header.php';
?>
<button id="btnScrollToTop" > <ion-icon name="arrow-up" aria-hidden="true"></ion-icon></button>

<main>
  <div class="form-container">
    <h1>Submit a Question</h1>

    <?php if (!empty($error)): ?>
      <div class="error-message" role="alert"><?= htmlspecialchars($error) ?></div>
      <div class="button-container">
        <a href="app/login.php" class="button">Go to Login</a>
        <button onclick="history.back()" class="button back-button">Back</button>
      </div>

    <?php else: ?>

      <?php if (!empty($_SESSION['success'])): ?>
        <div class="success-message" role="alert">
          <?= htmlspecialchars($_SESSION['success']) ?><br>
          <a href="answer_question.php?id=<?= (int)$_SESSION['last_qid'] ?>"
             class="view-button">
            View your question »
          </a>
        </div>
        <?php unset($_SESSION['success'], $_SESSION['last_qid']); ?>

      <?php elseif (!empty($_SESSION['error'])): ?>
        <div class="error-message" role="alert"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <form action="app/submit_question.php" method="POST">
        <label for="module">Module:</label>
        <select id="module" name="module" required aria-required="true" aria-label="Select module">
          <option value="">-Choose Module-</option>
          <?php foreach($modules as $m): ?>
            <option value="<?= htmlspecialchars($m['code']) ?>">
              <?= htmlspecialchars($m['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <label for="title">Question Title:</label>
        <input type="text" id="title" name="title" placeholder="Short title: e.g. ‘Trouble reading JSON file in PHP'" maxlength="100"  aria-required="true" aria-label="Question title" required>

        <label for="question">Your Question:</label>
        <textarea id="question" name="question" rows="5" placeholder="Explain your problem step by step so staff can help you best  " required aria-required="true" aria-label="Question text"></textarea>

        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" onclick="window.location.href='main.php'">Home</button>
      </form>

    <?php endif; ?>
  </div>
</main>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>

