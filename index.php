<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="text-center">
            <h1 class="display-4">Bienvenue sur SmartTech</h1>
            <p class="lead">Gérez vos fichiers en toute simplicité.</p>
            <a href="login.php" class="btn btn-primary">Connexion</a>
            <a href="register.php" class="btn btn-success">Inscription</a>
        </div>
    </div>
</body>
</html>