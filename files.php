<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'db.php';

$receiver_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM files WHERE receiver_id = :receiver_id");
$stmt->execute(['receiver_id' => $receiver_id]);
$files = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fichiers reçus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Fichiers reçus</h3>
                    </div>
                    <div class="card-body">
                        <?php if (count($files) > 0): ?>
                            <ul class="list-group">
                                <?php foreach ($files as $file): ?>
                                    <li class="list-group-item">
                                        <a href="<?php echo $file['file_path']; ?>" class="btn btn-link"><?php echo $file['file_name']; ?></a>
                                        <span class="text-muted">(Envoyé par l'utilisateur ID: <?php echo $file['sender_id']; ?>)</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-center">Aucun fichier reçu.</p>
                        <?php endif; ?>
                        <div class="text-center mt-3">
                            <a href="dashboard.php" class="btn btn-secondary">Retour au tableau de bord</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>