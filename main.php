<!DOCTYPE html>
<html>
<body>
<?php 
session_start();
$dataFile = __DIR__ . '/data/questions.json';
$qs = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
usort($qs, fn($a,$b)=> strcmp($b['created_at'],$a['created_at']));
$recent = $qs;

$pageTitle = "Home Page";
include('header.php'); ?>

<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<button id="btnScrollToTop" > <ion-icon name="arrow-up"></ion-icon></button>\

<script src="js/script.js"></script>


<main class="mt-5 pt-5">
  <div class="container-fluid">

    <section class="welcomeBanner text-center my-5">
      <h1 class="display-4">Welcome to LJMU Module Q&A</h1>
      <p class="lead">Ask questions, get answers, and explore all module related questions from others in one place.</p>
      <?php if(!isset($_SESSION['username'])): ?>
        <a href="app/login.php" class="login-button">Log in to get started</a>
      <?php endif; ?>
    </section>

    <section class="features text-center my-5" aria-labelledby="featuresHeading">
      <h2 id="featuresHeading" class="feature-heading">What you can do</h2>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="featureCard card h-100">
            <img src="img/ask.png" alt="Ask icon" class="featureIcon img-responsive">
            <div class="card-body">
              <h3 class="feature-title">Submit Questions</h3>
              <p class="feature-text">Students can submit module-specific questions and track their status.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="featureCard card h-100">
            <img src="img/answer.png" alt="Answer icon" class="featureIcon mx-auto mt-3">
            <div class="card-body">
              <h3 class="feature-title">Get Answers</h3>
              <p class="feature-text">Staff can post answers; everyone can browse answered questions.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="featureCard card h-100">
            <img src="img/vote.jpg" alt="Vote icon" class="featureIcon mx-auto mt-3">
            <div class="card-body">
              <h3 class="feature-title">Vote & Prioritize</h3>
              <p class="feature-text">Students vote on questions to raise their priority.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

        <!-- call to action buttons for easier nav -->
    <section class="cta text-center my-5">
      <h2 class="cta-heading">Ready to participate?</h2>
      <a href="submit_question.php" class="btn btn-success mx-2">Submit a Question</a>
      <a href="view_question.php" class="btn btn-outline-secondary mx-2">View Questions</a>
    </section>


    
    <!--  Recent questions slider-->
    <section aria-labelledby="recentHeading" class="recent-slider-section my-5">
      <h2 id="recentHeading" class="recent-heading">Recent Questions</h2>

      <?php if (count($recent)): ?>
        <div class="recent-slider">
          <button class="slider-arrow prev">&lsaquo;</button>
          <div class="slider-window">
            <div class="slider-track">
              <?php foreach($recent as $q): ?>
                <div class="slider-card">
                  <div class="question-card">
                    <div class="question-card-header">
                      <span class="module-badge"><?=htmlspecialchars($q['module_name'] ?? $q['module_code'])?></span>
                      <span class="status-badge <?=$q['status']?>"><?=ucfirst($q['status'])?></span>
                    </div>
                    <div class="question-card-body">
                      <h2 class="question-title"><?=htmlspecialchars($q['title'])?></h2>
                      <small class="question-meta"><?=substr($q['created_at'],0,10)?></small>
                    </div>
                    <div class="question-card-footer">
                      <a href="answer_question.php?id=<?= (int)$q['id'] ?>" class="view-button">View</a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <button class="slider-arrow next">&rsaquo;</button>
        </div>
      <?php else: ?>
        <p class="recent-empty">No questions have been asked yet.</p>
      <?php endif; ?>
    </section>
    </div>
</main>

<?php include('footer.php'); ?>

</body>
</html>
