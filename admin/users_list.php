<?php
$pageTitle = "Admin - Utilisateurs";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/header.php";
requireAdmin();

$users = $pdo->query("SELECT id,name,email,role,created_at FROM users ORDER BY created_at DESC")->fetchAll();
?>

<h1>Utilisateurs</h1>

<table style="width:100%">
  <thead>
    <tr><th>ID</th><th>Nom</th><th>Email</th><th>Role</th><th>Créé</th><th>Action</th></tr>
  </thead>
  <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?= (int)$u['id'] ?></td>
        <td><?= e($u['name']) ?></td>
        <td><?= e($u['email']) ?></td>
        <td><?= e($u['role']) ?></td>
        <td><?= e($u['created_at']) ?></td>
        <td>
          <?php if ((int)$u['id'] !== (int)($_SESSION['user']['id'] ?? 0)): ?>
            <a class="btn btn-danger" href="user_delete.php?id=<?= (int)$u['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
          <?php else: ?>
            <span class="muted">moi</span>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require __DIR__ . "/../includes/footer.php"; ?>
