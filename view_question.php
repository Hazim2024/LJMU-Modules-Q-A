<?php
session_start();
$pageTitle = "All Questions";
include 'header.php';


// loading modules.json so we can filter to just those modules  staff teach otherwise all modules are shown to students
$allMods = json_decode(file_get_contents(__DIR__.'/data/modules.json'), true)['modules'];
$visibleMods = [];
if (!empty($_SESSION['role'])) {
  if ($_SESSION['role']==='staff') {
    foreach($allMods as $m){
      if ($m['tutor'] === $_SESSION['username']) {
        $visibleMods[] = $m;
      }
    }
  } elseif ($_SESSION['role']==='student') {
    $visibleMods = $allMods;
  }
}

?>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
<button id="btnScrollToTop" > <ion-icon name="arrow-up"></ion-icon></button>
<main>
  <div class="questions-container">

    

    <!-- ── Module select (staff & student) ── -->
    <?php if (!empty($visibleMods)): ?>
      <div class="mb-4 text-center">
        <select id="module-select" aria-label="Filter by module" class="form-control w-50 d-inline-block">
        <option value="" disabled <?= empty($_GET['module']) ? 'selected' : '' ?>>Choose module filter</option>
          <option value="">All <?= $_SESSION['role'] === 'staff' ? 'my' : '' ?> modules</option>
          <?php foreach($visibleMods as $m): ?>
            <option value="<?= htmlspecialchars($m['code']) ?>"
              <?= (($_GET['module']??'') === $m['code'] ? 'selected' : '') ?>>
              <?= htmlspecialchars($m['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>



    <!--  Live -->
    <div class="mb-4 text-center">
      <input type="text" id="search-input" placeholder="Search questions…" class="form-control w-50 d-inline-block" aria-label="Live search questions">
    </div>
    <!-- ——— Filter nav pills-->
    <ul class="filter-pills">
      <?php foreach (['all'=>'All','unanswered'=>'Unanswered','answered'=>'Answered'] as $key=>$lab): ?>
        <li class="filter-pill" role="presentation">
          <a href="?filter=<?= $key ?>" class="filter-link <?= ($_GET['filter']??'all')===$key ? 'active':''?>" data-filter="<?= $key ?>"> <?= htmlspecialchars($lab) ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
    <div id="flash-message" class="flash" role="alert" aria-live="assertive" aria-atomic="true">
      <span class="flash__text"></span>
      <button type="button" class="flash__close" aria-label="Dismiss message">&times;</button>
    </div>


    <!-- ——— cards will be injected here by js/questions.js-->
    <div id="questions-grid" class="questions-grid" aria-live="polite">
    </div>
  </div>
</main>
<?php include 'footer.php'; ?>
<script>
  
// expose the current user role to JS
window.USER_ROLE = <?= json_encode($_SESSION['role'] ?? '') ?>;
</script>
<script src="js/questions.js"></script>
<script src="js/script.js"></script>
