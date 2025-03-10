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


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_email'])) {
    $to = $_POST['email_to'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $headers = "From: " . $user['email'];
    
    if (mail($to, $subject, $message, $headers)) {
        $success = "E-mail envoyé avec succès.";
    } else {
        $error = "Erreur lors de l'envoi de l'e-mail.";
    }
}

$stmt_received = $conn->prepare("SELECT * FROM emails_recus WHERE user_id = :user_id ORDER BY received_at DESC");
$stmt_received->execute(['user_id' => $user_id]);
$emails_recus = $stmt_received->fetchAll();
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
        
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Envoyer un e-mail</div>
                    <div class="card-body">
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"> <?php echo $success; ?> </div>
                        <?php endif; ?>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"> <?php echo $error; ?> </div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email_to" class="form-label">Destinataire</label>
                                <input type="email" name="email_to" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Objet</label>
                                <input type="text" name="subject" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea name="message" class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" name="send_email" class="btn btn-primary w-100">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">E-mails reçus</div>
                    <div class="card-body">
                        <?php if (count($emails_recus) > 0): ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Expéditeur</th>
                                        <th scope="col">Objet</th>
                                        <th scope="col">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($emails_recus as $email): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($email['sender_email']); ?></td>
                                            <td><?php echo htmlspecialchars($email['subject']); ?></td>
                                            <td><?php echo htmlspecialchars($email['received_at']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>Aucun e-mail reçu.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
