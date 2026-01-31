<?php
require __DIR__ . "/includes/auth.php";
// Détruit la session pour déconnecter l'utilisateur
session_destroy();
header("Location: index.php");
exit;
?>