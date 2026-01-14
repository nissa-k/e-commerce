<?php
$pageTitle = "Checkout";
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

requireLogin();
cartInit();

$ids = array_keys($_SESSION['cart']);
if (!$ids) {
  flash('error', "Panier vide.");
  header("Location: cart.php");
  exit;
}

$in = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id,title,price FROM courses WHERE id IN ($in) AND published=1");
$stmt->execute($ids);
$courses = $stmt->fetchAll();

if (!$courses) {
  flash('error', "Aucun cours valide dans le panier.");
  header("Location: cart.php");
  exit;
}

$total = 0.0;
foreach ($courses as $c) {
  $qty = (int)($_SESSION['cart'][$c['id']] ?? 0);
  $total += (float)$c['price'] * $qty;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $address = trim($_POST['billing_address'] ?? '');
  $city = trim($_POST['city'] ?? '');
  $postal = trim($_POST['postal_code'] ?? '');

  if ($address === '') $errors[] = "Adresse obligatoire";
  if ($city === '') $errors[] = "Ville obligatoire";
  if ($postal === '') $errors[] = "Code postal obligatoire";

  if (!$errors) {
    $pdo->beginTransaction();
    try {
      // 1) Invoice (facture)
      $inv = $pdo->prepare("INSERT INTO invoice(user_id, amount, billing_address, city, postal_code)
                            VALUES(?,?,?,?,?)");
      $inv->execute([(int)$_SESSION['user']['id'], $total, $address, $city, $postal]);

      // 2) Order shop (commande “propre”)
      $o = $pdo->prepare("INSERT INTO orders_shop(user_id, status, total_amount) VALUES(?,?,?)");
      $o->execute([(int)$_SESSION['user']['id'], 'paid', $total]);
      $orderId = (int)$pdo->lastInsertId();

      $oi = $pdo->prepare("INSERT INTO order_items(order_id, course_id, unit_price) VALUES(?,?,?)");
      $enroll = $pdo->prepare("INSERT IGNORE INTO enrollments(user_id, course_id) VALUES(?,?)");

      foreach ($courses as $c) {
        $qty = (int)($_SESSION['cart'][$c['id']] ?? 0);
        for ($i=0; $i<$qty; $i++) {
          $oi->execute([$orderId, (int)$c['id'], (float)$c['price']]);
        }
        // accès au cours après achat
        $enroll->execute([(int)$_SESSION['user']['id'], (int)$c['id']]);
      }

      // 3) Paiement fake
      $pay = $pdo->prepare("INSERT INTO payments(order_id, provider, status) VALUES(?,?,?)");
      $pay->execute([$orderId, 'fake', 'succeeded']);

      $pdo->commit();

      cartClear();
      flash('success', "Paiement simulé OK ✅ (facture créée) — tes cours sont dans Mes cours.");
      header("Location: my_courses.php");
      exit;

    } catch (Throwable $e) {
      $pdo->rollBack();
      $errors[] = "Erreur checkout : " . $e->getMessage();
    }
  }
}
?>

<h1>Checkout</h1>
<p>Total : <strong><?= number_format($total, 2) ?> €</strong></p>

<form method="post">
  <p>
    <input name="billing_address" placeholder="Adresse de facturation" required>
  </p>
  <p>
    <input name="city" placeholder="Ville" required>
  </p>
  <p>
    <input name="postal_code" placeholder="Code postal" required>
  </p>
  <button class="btn btn-primary" type="submit">Payer (simulation)</button>
  <a class="btn" href="cart.php">Retour</a>
</form>

<?php foreach ($errors as $eMsg): ?>
  <p style="color:red"><?= e($eMsg) ?></p>
<?php endforeach; ?>

<?php require __DIR__ . "/includes/footer.php"; ?>
