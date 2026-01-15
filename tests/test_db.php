<?php
require __DIR__ . "/config/db.php";

// Test de la connexion et affichage des tables existantes grace $pdo = new PDO(...); dans config/db.php
$stmt = $pdo->query("SHOW TABLES");
// grace au SHOW TABLES on recupere la liste des tables on la stocke dans $tables fetchAll() sert a recuperer toutes les lignes du resultat de la requete sql
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "Connexion DB OK <br>";
echo "Tables trouvées : " . implode(", ", $tables);
?>