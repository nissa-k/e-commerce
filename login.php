<?php
$pageTitle = "Connexion";
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // evite le brute-force simple ralentie la page login de 200ms
  usleep(200000); 
  verify_csrf();
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  // Validation basique de l'email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Identifiants invalides";
  } else {
    $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
  }

  // Vérification du mot de passe 
  if (!$user || !password_verify($password, $user['password'])) {
    $error = "Identifiants invalides";
  } else {
    
  // Connexion réussie
    unset($user['password']);
    $_SESSION['user'] = $user;
    session_regenerate_id(true);
    flash('success', "Bienvenue " . $user['name'] . " !");
    header("Location: index.php");
    exit;
  }
}
?>

<h1>Connexion</h1>

<form method="post">
  <?= csrf_field() ?>
  <p><input name="email" placeholder="Email" required></p>
  <p><input type="password" name="password" placeholder="Mot de passe" required></p>
  <button class="btn btn-primary">Se connecter</button>

  <p style="margin-top:10px">
    Pas encore de compte ? <a href="register.php">S'inscrire</a>
  </p>
</form>

<?php if ($error) echo "<p style='color:red'>".e($error)."</p>"; ?>

<?php require __DIR__ . "/includes/footer.php"; ?>
