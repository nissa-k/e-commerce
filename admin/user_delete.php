<?php
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/auth.php";
require __DIR__ . "/../includes/funcions.php";

requireAdmin();

$id = (int)($_GET['id'] ?? 0);

if ($id === (int)($_SESSION['user']['id'] ?? 0)) {
  flash('error', "Tu ne peux pas te supprimer toi-même.");
  header("Location: users_list.php");
  exit;
}

$pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);

flash('success', "Utilisateur supprimé.");
header("Location: users_list.php");
exit;
