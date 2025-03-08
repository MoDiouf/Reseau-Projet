<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="text-center">
            <h1 class="display-4">Bienvenue, <?php echo $user['name']; ?></h1>
            <div class="mt-4">
                <a href="upload.php" class="btn btn-primary">Envoyer un fichier</a>
                <a href="files.php" class="btn btn-secondary">Fichiers reçus</a>
                <a href="logout.php" class="btn btn-danger">Déconnexion</a>
            </div>
        </div>
    </div>
</body>
</html>