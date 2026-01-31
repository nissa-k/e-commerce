<?php
$pageTitle = "Admin";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/header.php";
requireAdmin();
?>
<section class="admin-section">

<h1>Back-office</h1>

<p>
  Bienvenue dans le panneau d'administration. 
  Utilisez les liens ci-dessous pour gérer les cours et les utilisateurs.
</p>
<p>
  Assurez-vous de manipuler ces options avec précaution, car elles affectent directement le contenu et les utilisateurs de la plateforme.
</p>
<p>
  Aucune modifiation ne sera confirmée par email. Assurez vous de bien avoir l'accord de votre administrateur principal.
</p>
<div class="admin-menu">
  <a class="btn btn-primary" href="courses_list.php">Gérer les cours (CRUD)</a>
  <a class="btn btn-primary" href="users_list.php">Gérer les utilisateurs</a>
</div>
</section>
<?php require __DIR__ . "/../includes/footer.php"; ?>
