<?php
$pageTitle = "Catalogue";
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

$catSlug = trim($_GET['cat'] ?? '');
$q = trim($_GET['q'] ?? '');

$cats = $pdo->query("SELECT id, slug, name FROM categories ORDER BY name")->fetchAll();

$sql = "SELECT c.*, ca.name AS category_name, ca.slug AS category_slug
        FROM courses c
        JOIN categories ca ON ca.id = c.category_id
        WHERE c.published = 1";
$params = [];

if ($catSlug !== '') {
  $sql .= " AND ca.slug = :cat";
  $params['cat'] = $catSlug;
}

if ($q !== '') {
  $sql .= " AND (c.title LIKE :q OR c.description LIKE :q)";
  $params['q'] = "%$q%";
}

$sql .= " ORDER BY c.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$courses = $stmt->fetchAll();
?>

<h1>Catalogue</h1>

<form method="get" style="margin:12px 0; display:flex; gap:10px; flex-wrap:wrap;">
  <input name="q" placeholder="Rechercher..." value="<?= e($q) ?>">
  <select name="cat">
    <option value="">Toutes catégories</option>
    <?php foreach ($cats as $c): ?>
      <option value="<?= e($c['slug']) ?>" <?= $catSlug === $c['slug'] ? 'selected' : '' ?>>
        <?= e($c['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <button class="btn btn-primary" type="submit">Filtrer</button>
</form>

<div class="grid">
  <?php foreach ($courses as $c): ?>
    <div class="card">
      <h3><?= e($c['title']) ?></h3>
      <p><small class="muted"><?= e($c['category_name']) ?> • <?= e($c['level']) ?></small></p>
      <p><?= e(mb_strimwidth($c['description'], 0, 120, '...')) ?></p>
      <p><strong><?= number_format((float)$c['price'], 2) ?> €</strong></p>

      <a class="btn" href="course.php?slug=<?= urlencode($c['slug']) ?>">Voir</a>

      <form method="post" action="cart.php" style="display:inline">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="course_id" value="<?= (int)$c['id'] ?>">
        <button class="btn btn-primary" type="submit">Ajouter</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . "/includes/footer.php"; ?>
