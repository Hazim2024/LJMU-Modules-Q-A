<?php
session_start();

$usersData = json_decode(file_get_contents(__DIR__ . "/../data/users.json"), true);

// for error message if no matches found
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $found = false;
    foreach ($usersData['users'] as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            // Success!
            $_SESSION['username'] = $username;
            $_SESSION['role']     = $user['role'];
            header("Location: ../main.php");
            exit;
        }
    }
    //otherwise error message
    $error = ' Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Page</title>
  <link rel="stylesheet" href="../css/style.css">  
</head>
<body>
  <div class="login-container">
    <h1>Login</h1>

    <?php if ($error): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label class="label-1" for="username">Username:</label>
        <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

        <label class="label-1" for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
        <button type="button" onclick="window.location.href='../main.php'">Home</button>
    </form>
  </div>
</body>
</html>
