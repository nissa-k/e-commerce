<?php
// verifie si la session est deja demarree
if (session_status() === PHP_SESSION_NONE) {
  ini_set('session.use_strict_mode', '1');
  $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

  session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'secure' => $secure,
    'samesite' => 'Lax',
  ]);

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
