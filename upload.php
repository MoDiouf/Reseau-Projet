<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_path = 'uploads/' . basename($file_name);

    if (move_uploaded_file($file_tmp, $file_path)) {
        $stmt = $conn->prepare("INSERT INTO files (sender_id, receiver_id, file_name, file_path) VALUES (:sender_id, :receiver_id, :file_name, :file_path)");
        $stmt->execute(['sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'file_name' => $file_name, 'file_path' => $file_path]);

        $success = "Fichier uploadé avec succès.";
    } else {
        $error = "Erreur lors de l'upload du fichier.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploader un fichier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Uploader un fichier</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="receiver_id" class="form-label">Destinataire (ID utilisateur)</label>
                                <input type="number" name="receiver_id" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label">Fichier</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Envoyer</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="dashboard.php">Retour au tableau de bord</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>