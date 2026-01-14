<?php
// verifie si la session est deja demarree
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Vérifie si un utilisateur est connecté
function isLoggedIn(): bool {
  return isset($_SESSION['user']);
}

// Vérifie si l'utilisateur connecté est un admin
function isAdmin(): bool {
  return isLoggedIn() && ($_SESSION['user']['role'] ?? '') === 'admin';
}

?>