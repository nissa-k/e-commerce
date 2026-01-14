<?php
$pageTitle = "Mes cours";
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

requireLogin();

$stmt = $pdo->prepare("SELECT c.id, c.title, c.slug, c.description
                       FROM enrollments e
                       JOIN courses c ON c.id = e.course_id
                       WHERE e.user_id = ?
                       ORDER BY e.created_at DESC");
$stmt->execute([(int)$_SESSION['user']['id']]);
$courses = $stmt->fetchAll();
?>

<h1>Mes cours</h1>

<?php if (!$courses): ?>
  <p>Tu n’as aucun cours pour le moment.</p>
  <a class="btn btn-primary" href="courses.php">Acheter un cours</a>
<?php else: ?>
  <div class="grid">
    <?php foreach ($courses as $c): ?>
      <div class="card">
        <h3><?= e($c['title']) ?></h3>
        <p><?= e(mb_strimwidth($c['description'], 0, 120, '...')) ?></p>
        <a class="btn btn-primary" href="learn.php?slug=<?= urlencode($c['slug']) ?>">Accéder</a>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require __DIR__ . "/includes/footer.php"; ?>
