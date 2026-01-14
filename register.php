<?php
$pageTitle = "Inscription";
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $password2 = $_POST['password2'] ?? '';

  if ($name === '') $errors[] = "Nom obligatoire";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
  if (strlen($password) < 6) $errors[] = "Mot de passe trop court (min 6)";
  if ($password !== $password2) $errors[] = "Les mots de passe ne correspondent pas";

  if (!$errors) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) $errors[] = "Email déjà utilisé";
  }

  if (!$errors) {

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
      "INSERT INTO users (name, email, password, role)
       VALUES (:n, :e, :p, 'user')"
    );

    $stmt->execute([
      'n' => $name,
      'e' => $email,
      'p' => $hash
    ]);

    flash('success', "Compte créé avec succès. Connecte-toi.");
    header("Location: login.php");
    exit;
  }
}
?>

<h1>Inscription</h1>

<form method="post">
  <p><input name="name" placeholder="Nom" required></p>
  <p><input name="email" placeholder="Email" required></p>
  <p><input type="password" name="password" placeholder="Mot de passe" required></p>
  <p><input type="password" name="password2" placeholder="Confirmer" required></p>
  <button class="btn btn-primary">S'inscrire</button>
</form>

<?php foreach ($errors as $eMsg) echo "<p style='color:red'>".e($eMsg)."</p>"; ?>

<?php require __DIR__ . "/includes/footer.php"; ?>
