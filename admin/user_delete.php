<?php
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/auth.php";
require __DIR__ . "/../includes/functions.php";

requireAdmin();

// Récupérer l'ID de l'utilisateur à supprimer
$id = (int)($_GET['id'] ?? 0);

// Empêcher la suppression de soi-même
if ($id === (int)($_SESSION['user']['id'] ?? 0)) {
  flash('error', "Tu ne peux pas te supprimer toi-même.");
  header("Location: users_list.php");
  exit;
}

// Supprimer l'utilisateur
$pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);

flash('success', "Utilisateur supprimé.");
header("Location: users_list.php");
exit;
