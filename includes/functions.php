<?php


function e($s): string {
  return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function flash(string $key, ?string $msg = null): ?string {
  if ($msg !== null) {
    $_SESSION['flash'][$key] = $msg;
    return null;
  }
  $val = $_SESSION['flash'][$key] ?? null;
  unset($_SESSION['flash'][$key]);
  return $val;
}

function requireLogin(): void {
  if (!isset($_SESSION['user'])) {
    flash('error', "Tu dois être connecté.");
    header("Location: login.php");
    exit;
  }
}

function requireAdmin(): void {
  if (!isset($_SESSION['user']) || (($_SESSION['user']['role'] ?? '') !== 'admin')) {
    flash('error', "Accès admin refusé.");
    header("Location: ../login.php");
    exit;
  }
}

// --- PANIER SESSION ---
function cartInit(): void {
  if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // [course_id => qty]
  }
}

// Ajoute un cours au panier
function cartAdd(int $id, int $qty = 1): void {
  cartInit();
  $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
  if ($_SESSION['cart'][$id] <= 0) unset($_SESSION['cart'][$id]);
}


// Retire un cours du panier
function cartRemove(int $id): void {
  cartInit();
  unset($_SESSION['cart'][$id]);
}

function cartClear(): void {
  $_SESSION['cart'] = [];
}

function cartCount(): int {
  cartInit();
  return array_sum($_SESSION['cart']);
}

// --- CSRF PROTECTION ---
// Génère et retourne un token CSRF
function csrf_token(): string {
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

// Génère le champ caché CSRF pour les formulaires
function csrf_field(): string {
  return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

// Vérifie le token CSRF dans les formulaires
function verify_csrf(): void {
  $token = $_POST['csrf_token'] ?? '';
  if (!$token || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    http_response_code(403);
    die('Action refusée (CSRF).');
  }
}
?>
