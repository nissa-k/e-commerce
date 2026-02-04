<?php
$pageTitle = "Panier";
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

cartInit();

// Gérer les actions du panier (ajout, suppression, vider)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  verify_csrf();

  // Récupérer l'action et l'ID du cours depuis le formulaire
  $action = $_POST['action'] ?? '';
  $courseId = (int)($_POST['course_id'] ?? 0);

  if ($action === 'add' && $courseId > 0) {
    cartAdd($courseId, 1);
    flash('success', "Ajouté au panier.");
    header("Location: cart.php");
    exit;
  }

  if ($action === 'remove' && $courseId > 0) {
    cartRemove($courseId);
    flash('success', "Supprimé du panier.");
    header("Location: cart.php");
    exit;
  }

  if ($action === 'clear') {
    cartClear();
    flash('success', "Panier vidé.");
    header("Location: cart.php");
    exit;
  }
}

// Récupérer les cours dans le panier et calculer le total
$ids = array_keys($_SESSION['cart']);
$courses = [];
$total = 0.0;

// Si le panier n'est pas vide, récupérer les détails des cours
if ($ids) {
  $in = implode(',', array_fill(0, count($ids), '?'));
  $stmt = $pdo->prepare("SELECT id,title,slug,price FROM courses WHERE id IN ($in) AND published=1");
  $stmt->execute($ids);
  $courses = $stmt->fetchAll();

  // Calculer le total du panier en fonction des prix et quantités
  foreach ($courses as $c) {
    $qty = (int)($_SESSION['cart'][$c['id']] ?? 0);
    $total += (float)$c['price'] * $qty;
  }
}
?>

<h1>Panier</h1>

<?php if (!$ids): ?>
  <p>Ton panier est vide.</p>
  <a class="btn btn-primary" href="courses.php">Aller au catalogue</a>
<?php else: ?>
  <table class="table" style="width:100%">
    <thead>
      <tr>
        <th>Cours</th><th>Prix</th><th>Qté</th><th>Total</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <!-- Afficher chaque cours dans le panier avec ses détails et une option pour le supprimer -->
      <?php foreach ($courses as $c): 
        $qty = (int)($_SESSION['cart'][$c['id']] ?? 0);
        $line = (float)$c['price'] * $qty;
      ?>
      <tr>
        <!-- Lien vers la page du cours avec son titre -->
        <td><a href="course.php?slug=<?= urlencode($c['slug']) ?>"><?= e($c['title']) ?></a></td>
        <td><?= number_format((float)$c['price'], 2) ?> €</td>
        <td><?= $qty ?></td>
        <td><?= number_format($line, 2) ?> €</td>
        <td>
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="remove">
            <input type="hidden" name="course_id" value="<?= (int)$c['id'] ?>">
            <button class="btn btn-danger" type="submit">Supprimer</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p><strong>Total : <?= number_format($total, 2) ?> €</strong></p>

  <div style="display:flex;gap:10px;flex-wrap:wrap;">
    <a class="btn btn-primary" href="checkout.php">Passer commande</a>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="clear">
      <button class="btn" type="submit">Vider le panier</button>
    </form>
  </div>
<?php endif; ?>

<?php require __DIR__ . "/includes/footer.php"; ?>
