<?php
$pageTitle = "Admin - Ajouter cours";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/header.php";
requireAdmin();

// Récupérer les catégories pour le select
$cats = $pdo->query("SELECT id, slug, name FROM categories ORDER BY name")->fetchAll();
$errors = [];

// Traitement du formulaire de création
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

  // upload image 
  // image par défaut null car pas obligatoire
  $image = null;

  // si nouvelle image uploadée
  if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

  // valider l'extension de l'image
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    // extensions autorisées
    $allowed = ['jpg','jpeg','png','webp'];
    if (!in_array($ext, $allowed, true)) {
      $errors[] = "Image invalide (jpg/jpeg/png/webp)";
    } else {
      // créer le dossier uploads s'il n'existe pas et déplacer le fichier uploadé là-bas 
      @mkdir(__DIR__ . "/../public/uploads", 0777, true);

      // nom unique pour l'image
      $image = uniqid("course_", true) . "." . $ext;

      // Déplacer le fichier uploadé
      move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . "/../public/uploads/" . $image);
    }
  }

  if (!$errors) {
    try {
      // Insérer le cours dans la base de données
      $stmt = $pdo->prepare("
        INSERT INTO courses(category_id,title,slug,description,price,level,image,published)
        VALUES(?,?,?,?,?,?,?,?)
      ");
      $stmt->execute([$categoryId, $title, $slug, $desc, $price, $level, $image, $published]);

      flash('success', "Cours créé.");
      header("Location: courses_list.php");
      exit;
    } catch (Throwable $e) {
      $errors[] = $e->getMessage();
    }
  }
}
?>

<h1>Ajouter un cours</h1>

<form method="post" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <p><input name="title" placeholder="Titre" required></p>
  <p><input name="slug" placeholder="Slug (unique, ex: php-crud-admin)" required></p>

  <p>
    <select name="category_id" required>
      <option value="">-- Catégorie --</option>
      <?php foreach ($cats as $c): ?>
        <option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </p>

  <p>
    <select name="level">
      <option value="debutant">Débutant</option>
      <option value="intermediaire">Intermédiaire</option>
      <option value="avance">Avancé</option>
    </select>
  </p>

  <p><textarea name="description" placeholder="Description" rows="6" required></textarea></p>
  <p><input type="number" step="0.01" name="price" placeholder="Prix" value="0"></p>

  <p><input type="file" name="image" accept="image/*"></p>

  <p><label><input type="checkbox" name="published" checked> Publié</label></p>

  <button class="btn btn-primary">Créer</button>
  <a class="btn" href="courses_list.php">Retour</a>
</form>

<?php foreach ($errors as $m): ?>
  <p style="color:red"><?= e($m) ?></p>
<?php endforeach; ?>

<?php require __DIR__ . "/../includes/footer.php"; ?>
