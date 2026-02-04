<?php
$pageTitle = "Inscription";
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

$errors = [];

// Traitement du formulaire d'inscription si c est un POST request 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf();
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $password2 = $_POST['password2'] ?? '';

  //contrôles basiques de saisie
  if ($name === '') $errors[] = "Nom obligatoire";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
  if (strlen($password) < 6) $errors[] = "Mot de passe trop court (min 6)";

  // Vérifier la confirmation du mot de passe
  if ($password !== $password2) $errors[] = "Les mots de passe ne correspondent pas";

  // Vérifier si l'email est déjà utilisé
  if (!$errors) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) $errors[] = "Email déjà utilisé";
  }

  if (!$errors) {
// Tout est bon, créer l'utilisateur
  // Hasher le mot de passe pour la base de données
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Insérer dans la base de données
    $stmt = $pdo->prepare(
      "INSERT INTO users (name, email, password, role)
       VALUES (:n, :e, :p, 'user')"
    );

    // Exécuter l'insertion
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
  <?= csrf_field() ?>
  <p><input name="name" placeholder="Nom" required></p>
  <p><input name="email" placeholder="Email" required></p>
  <p><input type="password" name="password" placeholder="Mot de passe" required></p>
  <p><input type="password" name="password2" placeholder="Confirmer" required></p>
  <button class="btn btn-primary">S'inscrire</button>

  <p style="margin-top:10px">
    Avez-vous déjà un compte ? <a href="login.php">Se connecter</a>
  </p>
</form>

<?php foreach ($errors as $eMsg) echo "<p style='color:red'>".e($eMsg)."</p>"; ?>

<?php require __DIR__ . "/includes/footer.php"; ?>
