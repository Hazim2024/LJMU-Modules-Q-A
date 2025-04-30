<?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'Module Portal') ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  
  <script>
    function confirmLogout() {
      return confirm("Are you sure you want to log out?");
    }
  </script>
</head>
  <header>
    <h2 class="title">LJMU Module Q&A</h2>

    <nav class="navigation"  role="navigation" aria-label="Main menu">
      <ul class="nav_list">
        <a href="main.php" class="nav_link">Home</a>
        <a href="submit_question.php" class="nav_link">Submit Question</a>
        <a href="view_question.php" class="nav_link">View Questions</a>
        <?php if (isset($_SESSION['username'])): ?>
        

        <!-- everyone logged in -->
        <a href="app/logout.php" class="nav_link" onclick="return confirmLogout();">Logout</a>
      <?php else: ?>
        <!-- not logged in -->
        <a href="app/login.php" class="nav_link">Login</a>
      <?php endif; ?>
      </ul>
    </nav>
      <!-- Hamburger menu for mobile view -->
  <button 
    class="hamburger" 
    aria-label="Toggle navigation menu"
    aria-expanded="false"
    aria-controls="main-nav"
    onclick="toggleMenu()"
  >
    <span class="bar"></span>
    <span class="bar"></span>
    <span class="bar"></span>
  </button>
  </header>
  
