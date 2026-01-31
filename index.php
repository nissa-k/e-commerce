<?php
$pageTitle = "Accueil";
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

$cats = $pdo->query("SELECT id, slug, image, name FROM categories ORDER BY id")->fetchAll();
?>
<h1>Plateforme de vente de cours</h1>
<p>Choisis une catégorie et achète des cours en ligne.</p>

<div class="grid">
  <?php foreach ($cats as $c): ?>
    <div class="card">
      <img src="public/uploads/<?= e($c['image']) ?>" alt="<?= e($c['name']) ?>" class="img">

      <h3><?= e($c['name']) ?></h3>
      <a class="btn btn-primary" href="courses.php?cat=<?= urlencode($c['slug']) ?>">Voir les cours</a>
    </div>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . "/includes/footer.php"; ?>
