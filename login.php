<?php
$pageTitle = "Connexion";
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = :email");
  $stmt->execute(['email' => $email]);
  $user = $stmt->fetch();

  if (!$user || !password_verify($password, $user['password'])) {
    $error = "Identifiants invalides";
  } else {
    unset($user['password']);
    $_SESSION['user'] = $user;
    flash('success', "Bienvenue " . $user['name'] . " !");
    header("Location: index.php");
    exit;
  }
}
?>

<h1>Connexion</h1>

<form method="post">
  <p><input name="email" placeholder="Email" required></p>
  <p><input type="password" name="password" placeholder="Mot de passe" required></p>
  <button class="btn btn-primary">Se connecter</button>
</form>

<?php if ($error) echo "<p style='color:red'>".e($error)."</p>"; ?>

<?php require __DIR__ . "/includes/footer.php"; ?>
