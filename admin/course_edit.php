<?php
$pageTitle = "Admin - Edit";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/header.php";
requireAdmin();

// Récupérer l'ID du cours à éditer
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id=?");
$stmt->execute([$id]);
$course = $stmt->fetch();

if (!$course) {
  flash('error', "Cours introuvable.");
  header("Location: courses_list.php");
  exit;
}

// Récupérer les catégories pour le select
$cats = $pdo->query("SELECT id,name FROM categories ORDER BY name")->fetchAll();
$errors = [];

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf();
  $title = trim($_POST['title'] ?? '');
  $slug = trim($_POST['slug'] ?? '');
  $desc = trim($_POST['description'] ?? '');
  $price = (float)($_POST['price'] ?? 0);
  $level = $_POST['level'] ?? 'debutant';
  $published = isset($_POST['published']) ? 1 : 0;
  $categoryId = (int)($_POST['category_id'] ?? 0);

  if ($title === '') $errors[] = "Titre obligatoire";
  if ($slug === '') $errors[] = "Slug obligatoire";
  if ($desc === '') $errors[] = "Description obligatoire";
  if ($categoryId <= 0) $errors[] = "Catégorie obligatoire";

  // image actuelle (au cas où on ne change pas l'image)
  $image = $course['image'] ?? null;

  // si nouvelle image uploadée
  if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp'];
    if (!in_array($ext, $allowed, true)) {
      $errors[] = "Image invalide (jpg/jpeg/png/webp)";
    } else {
      @mkdir(__DIR__ . "/../public/uploads", 0777, true);
      $image = uniqid("course_", true) . "." . $ext;
      move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . "/../public/uploads/" . $image);
    }
  }

  if (!$errors) {
    try { 
      // Mettre à jour le cours dans la base de données upd = update
      $upd = $pdo->prepare("UPDATE courses
                            SET category_id=?, title=?, slug=?, description=?, price=?, level=?, image=?, published=?
                            WHERE id=?");
      $upd->execute([$categoryId, $title, $slug, $desc, $price, $level, $image, $published, $id]);

      flash('success', "Cours mis à jour.");
      header("Location: courses_list.php");
      exit;
      //throwable c est pour attraper toutes les erreurs/exceptions
    } catch (Throwable $e) {
      $errors[] = $e->getMessage();
    }
  }
}
?>

<h1>Modifier un cours</h1>

<form method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <p><input name="title" value="<?= e($course['title']) ?>" required></p>
  <p><input name="slug" value="<?= e($course['slug']) ?>" required></p>

  <p>
    <select name="category_id" required>
      <?php foreach ($cats as $c): ?>
        <option value="<?= (int)$c['id'] ?>" <?= (int)$course['category_id']===(int)$c['id']?'selected':'' ?>>
          <?= e($c['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </p>

  <p>
    <select name="level">
      <option value="debutant" <?= $course['level']==='debutant'?'selected':'' ?>>Débutant</option>
      <option value="intermediaire" <?= $course['level']==='intermediaire'?'selected':'' ?>>Intermédiaire</option>
      <option value="avance" <?= $course['level']==='avance'?'selected':'' ?>>Avancé</option>
    </select>
  </p>

  <p><textarea name="description" rows="6" required><?= e($course['description']) ?></textarea></p>
  <p><input type="number" step="0.01" name="price" value="<?= e((string)$course['price']) ?>"></p>

  <p>Image actuelle : <?= !empty($course['image']) ? e($course['image']) : '-' ?></p>
  <p><input type="file" name="image" accept="image/*"></p>

  <p><label><input type="checkbox" name="published" <?= (int)$course['published'] ? 'checked' : '' ?>> Publié</label></p>

  <button class="btn btn-primary">Enregistrer</button>
  <a class="btn" href="courses_list.php">Retour</a>
</form>

<?php foreach ($errors as $m): ?>
  <p style="color:red"><?= e($m) ?></p>
<?php endforeach; ?>

<?php require __DIR__ . "/../includes/footer.php"; ?>
