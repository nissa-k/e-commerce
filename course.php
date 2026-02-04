<?php
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

// Récupérer le cours par son slug
$slug = trim($_GET['slug'] ?? '');
if ($slug === '') {
  flash('error', "Cours introuvable.");
  header("Location: courses.php");
  exit;
}

// Préparer et exécuter la requête
$stmt = $pdo->prepare("SELECT c.*, ca.name AS category_name
                       FROM courses c
                       JOIN categories ca ON ca.id=c.category_id
                       WHERE c.slug=:slug AND c.published=1");
$stmt->execute(['slug' => $slug]);

// Récupérer le cours bonne pratique eviter SQL injection
$course = $stmt->fetch();

if (!$course) {
  flash('error', "Cours introuvable.");
  header("Location: courses.php");
  exit;
}

// Définir le titre de la page
$pageTitle = $course['title'];
?>

<h1><?= e($course['title']) ?></h1>
<p><small class="muted"><?= e($course['category_name']) ?> • Niveau : <?= e($course['level']) ?></small></p>

<p><?= nl2br(e($course['description'])) ?></p>
<p><strong><?= number_format((float)$course['price'], 2) ?> €</strong></p>

<form method="post" action="cart.php">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="add">
  <input type="hidden" name="course_id" value="<?= (int)$course['id'] ?>">
  <button class="btn btn-primary" type="submit">Ajouter au panier</button>
  <a class="btn" href="courses.php">Retour</a>
</form>

<?php require __DIR__ . "/includes/footer.php"; ?>
