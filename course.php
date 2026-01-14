<?php
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

$slug = trim($_GET['slug'] ?? '');
if ($slug === '') {
  flash('error', "Cours introuvable.");
  header("Location: courses.php");
  exit;
}

$stmt = $pdo->prepare("SELECT c.*, ca.name AS category_name
                       FROM courses c
                       JOIN categories ca ON ca.id=c.category_id
                       WHERE c.slug=:slug AND c.published=1");
$stmt->execute(['slug' => $slug]);
$course = $stmt->fetch();

if (!$course) {
  flash('error', "Cours introuvable.");
  header("Location: courses.php");
  exit;
}

$pageTitle = $course['title'];
?>

<h1><?= e($course['title']) ?></h1>
<p><small class="muted"><?= e($course['category_name']) ?> • Niveau : <?= e($course['level']) ?></small></p>

<?php if (!empty($course['thumbnail'])): ?>
  <img src="public/uploads/<?= e($course['thumbnail']) ?>" alt="" style="max-width:100%;border-radius:8px">
<?php endif; ?>

<p><?= nl2br(e($course['description'])) ?></p>
<p><strong><?= number_format((float)$course['price'], 2) ?> €</strong></p>

<form method="post" action="cart.php">
  <input type="hidden" name="action" value="add">
  <input type="hidden" name="course_id" value="<?= (int)$course['id'] ?>">
  <button class="btn btn-primary" type="submit">Ajouter au panier</button>
  <a class="btn" href="courses.php">Retour</a>
</form>

<?php require __DIR__ . "/includes/footer.php"; ?>
