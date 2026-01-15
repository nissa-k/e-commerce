<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/functions.php";
cartInit();
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($pageTitle) ? e($pageTitle) : "E-Courses" ?></title>
  <link rel="stylesheet" href="public/assets/style.css">
</head>
<body>
<header class="topbar">
  <div class="container">
    <a class="brand" href="index.php">E-Courses</a>

    <span>
      <?= e($_SESSION['user']['name'] ?? '') ?>
      <?php if (isAdmin()): ?>
      <strong style="color:red;">(ADMIN)</strong>
      <?php endif; ?>
    </span>

    <nav class="nav">
      <a href="courses.php">Catalogue</a>
      <a href="cart.php">Panier (<?= cartCount() ?>)</a>
      <a href="about.php">Qui sommes-nous ?</a>

      <?php if (isLoggedIn()): ?>
        <a href="my_courses.php">Mes cours</a>
        <?php if (isAdmin()): ?>
          <a href="admin/index.php">Admin</a>
        <?php endif; ?>
        <span class="muted">| <?= e($_SESSION['user']['name']) ?></span>
        <a href="logout.php">Déconnexion</a>
      <?php else: ?>
        <a href="register.php">Inscription</a>
        <a href="login.php">Connexion</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="container">
  <?php if ($m = flash('success')): ?>
    <p style="color:green"><?= e($m) ?></p>
  <?php endif; ?>
  <?php if ($m = flash('error')): ?>
    <p style="color:red"><?= e($m) ?></p>
  <?php endif; ?>
