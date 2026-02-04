<?php
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

requireLogin();

// Récupérer le cours par son slug avec vérification d'accès
$slug = trim($_GET['slug'] ?? '');
if ($slug === '') {
  flash('error', "Cours introuvable.");
  header("Location: my_courses.php");
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM courses WHERE slug=?");
$stmt->execute([$slug]);
$course = $stmt->fetch();

if (!$course) {
  flash('error', "Cours introuvable.");
  header("Location: my_courses.php");
  exit;
}

// Vérifie enrollment
$chk = $pdo->prepare("SELECT 1 FROM enrollments WHERE user_id=? AND course_id=?");
$chk->execute([(int)$_SESSION['user']['id'], (int)$course['id']]);
$hasAccess = (bool)$chk->fetchColumn();

if (!$hasAccess && !isAdmin()) {
  flash('error', "Tu n’as pas accès à ce cours.");
  header("Location: my_courses.php");
  exit;
}

// sections + lessons
$sections = $pdo->prepare("SELECT * FROM course_sections WHERE course_id=? ORDER BY position");
$sections->execute([(int)$course['id']]);
$sections = $sections->fetchAll();

$lessonsBySection = [];
if ($sections) {
  $secIds = array_column($sections, 'id');
  $in = implode(',', array_fill(0, count($secIds), '?'));
  $less = $pdo->prepare("SELECT * FROM course_lessons WHERE section_id IN ($in) ORDER BY section_id, position");
  $less->execute($secIds);
  foreach ($less->fetchAll() as $l) {
    $lessonsBySection[$l['section_id']][] = $l;
  }
}

// Définir le titre de la page 
$pageTitle = "Apprendre - " . $course['title'];
?>

<h1><?= e($course['title']) ?></h1>
<p><?= nl2br(e($course['description'])) ?></p>

<?php if (!$sections): ?>
  <p><em>Aucun contenu pour ce cours pour le moment. Veuillez ressayer plus tard.</em></p>
<?php else: ?>
  <?php foreach ($sections as $s): ?>
    <div class="card">
      <h3><?= e($s['title']) ?></h3>
      <?php foreach (($lessonsBySection[$s['id']] ?? []) as $l): ?>
        <div style="margin:10px 0;padding:10px;border-top:1px solid #eee">
          <strong><?= e($l['title']) ?></strong>
          <div><?= nl2br(e(mb_strimwidth($l['content'], 0, 400, '...'))) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<a class="btn" href="my_courses.php">Retour</a>

<?php require __DIR__ . "/includes/footer.php"; ?>
