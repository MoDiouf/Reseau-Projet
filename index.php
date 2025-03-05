<?php
require 'config.php';

// Ajouter un employé
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $stmt = $pdo->prepare("INSERT INTO employees (name, email, position) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $position]);
}

// Supprimer un employé
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
}

// Upload fichier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $filePath = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $stmt = $pdo->prepare("INSERT INTO files (file_name, file_path) VALUES (?, ?)");
        $stmt->execute([$file['name'], $filePath]);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Employés et Fichiers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Gestion des Employés</h2>
        <form action="index.php" method="POST" class="mb-3">
            <input type="text" name="name" placeholder="Nom" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="position" placeholder="Poste" required>
            <button type="submit" name="add_employee" class="btn btn-primary">Ajouter</button>
        </form>
        <table class="table">
            <tr><th>ID</th><th>Nom</th><th>Email</th><th>Poste</th><th>Action</th></tr>
            <?php
            $stmt = $pdo->query("SELECT * FROM employees");
            while ($emp = $stmt->fetch()) {
                echo "<tr><td>{$emp['id']}</td><td>{$emp['name']}</td><td>{$emp['email']}</td><td>{$emp['position']}</td>
                      <td><a href='index.php?delete={$emp['id']}' class='btn btn-danger'>Supprimer</a></td></tr>";
            }
            ?>
        </table>

        <h3 class="mt-5">Ajouter un fichier</h3>
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button type="submit" class="btn btn-success">Envoyer</button>
        </form>
        <h3 class="mt-5">Fichiers disponibles</h3>
        <ul class="list-group">
            <?php
            $stmt = $pdo->query("SELECT * FROM files");
            while ($file = $stmt->fetch()) {
                echo "<li class='list-group-item'><a href='{$file['file_path']}' download>{$file['file_name']}</a></li>";
            }
            ?>
        </ul>
    </div>
</body>
</html>
