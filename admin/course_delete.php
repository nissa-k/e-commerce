<?php
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/auth.php";
require __DIR__ . "/../includes/functions.php";

requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$pdo->prepare("DELETE FROM courses WHERE id=?")->execute([$id]);

flash('success', "Cours supprimé.");
header("Location: courses_list.php");
exit;
