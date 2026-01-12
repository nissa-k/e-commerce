<?php
require __DIR__ . "/config/db.php";

$stmt = $pdo->query("SHOW TABLES");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "Connexion DB OK <br>";
echo "Tables trouvées : " . implode(", ", $tables);
?>