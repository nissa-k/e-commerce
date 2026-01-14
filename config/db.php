<?php
$host = "localhost";
$dbname = "ecommerce_php";
$user = "root";
$pass = ""; 

// Connexion PDO via new PDO(...) une fonction native de PHP
// On utilise un bloc try/catch pour capturer les erreurs de connexion
try {
  $pdo = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
    $user,
    $pass,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
  );
} catch (PDOException $e) {
  die("Erreur DB : " . $e->getMessage());
}
?>