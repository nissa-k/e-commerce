<?php
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/auth.php";
require __DIR__ . "/../includes/functions.php";

$error = null;

// Traitement du formulaire de connexion admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf();

  // Récupérer les données du formulaire avec trim pour enlever les espaces inutiles et post pour récupérer les données envoyées par l'utilisateur ou c est email et password
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = :email");
  $stmt->execute(['email' => $email]);
  $user = $stmt->fetch();

  // Vérification du mot de passe et du rôle admin
  if (!$user || !password_verify($password, $user['password']) || $user['role'] !== 'admin') {
    $error = "Accès admin refusé";
  } else {
    unset($user['password']);
    $_SESSION['user'] = $user;
    flash('success', "Connecté en admin.");
    header("Location: index.php");
    exit;
  }
}
?>

<h1>Connexion Admin</h1>

<form method="post">
  <?= csrf_field() ?>
  <input name="email" placeholder="Email admin" required><br>
  <input type="password" name="password" placeholder="Mot de passe" required><br>
  <button class="btn btn-primary">Se connecter</button>
</form>

<?php if ($error) echo "<p style='color:red'>".e($error)."</p>"; ?>
