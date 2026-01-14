<?php
$pageTitle = "Admin";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/header.php";
requireAdmin();
?>

<h1>Back-office</h1>

<div style="display:flex;gap:10px;flex-wrap:wrap;">
  <a class="btn btn-primary" href="courses_list.php">Gérer les cours (CRUD)</a>
  <a class="btn" href="users_list.php">Gérer les utilisateurs</a>
</div>

<?php require __DIR__ . "/../includes/footer.php"; ?>
