<?php
$pageTitle = "Admin - Cours";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/header.php";
requireAdmin();

// Récupérer tous les cours avec leur catégorie
$courses = $pdo->query("SELECT c.id, c.title, c.slug, c.price, c.level, c.published, ca.name AS category_name
                        FROM courses c
                        JOIN categories ca ON ca.id=c.category_id
                        ORDER BY c.created_at DESC")->fetchAll();
?>

<h1>Gestion des cours</h1>
<a class="btn btn-primary" href="courses_create.php">+ Ajouter</a>

<table style="width:100%;margin-top:10px">
  <thead>
    <tr>
      <th>ID</th><th>Titre</th><th>Catégorie</th><th>Prix</th><th>Niveau</th><th>Publié</th><th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($courses as $c): ?>
      <tr>
        <td><?= (int)$c['id'] ?></td>
        <td><?= e($c['title']) ?></td>
        <td><?= e($c['category_name']) ?></td>
        <td><?= number_format((float)$c['price'],2) ?> €</td>
        <td><?= e($c['level']) ?></td>
        <td><?= (int)$c['published'] ? 'Oui' : 'Non' ?></td>
        <td>
          <a class="btn" href="course_edit.php?id=<?= (int)$c['id'] ?>">Edit</a>
          <a class="btn btn-danger" href="course_delete.php?id=<?= (int)$c['id'] ?>" onclick="return confirm('Supprimer ?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require __DIR__ . "/../includes/footer.php"; ?>
