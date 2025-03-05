<?php
require 'config.php';
session_start();

if (!isset($_SESSION['employee_id'])) {
    die(json_encode(["message" => "Vous devez être connecté pour envoyer un fichier"]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['receiver_id'])) {
    $sender_id = $_SESSION['employee_id'];
    $receiver_id = $_POST['receiver_id'];
    $file = $_FILES['file'];
    $uploadDir = 'uploads/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $stmt = $pdo->prepare("SELECT id FROM employees WHERE id = ?");
    $stmt->execute([$receiver_id]);
    if (!$stmt->fetch()) {
        die(json_encode(["message" => "Erreur : L'ID du destinataire n'existe pas."]));
    }
    
    $filePath = $uploadDir . basename($file['name']);
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        $stmt = $pdo->prepare("INSERT INTO files (sender_id, receiver_id, file_name, file_path) VALUES (?, ?, ?, ?)");
        $stmt->execute([$sender_id, $receiver_id, $file['name'], $filePath]);
        echo json_encode(["message" => "Fichier envoyé avec succès", "file" => $filePath]);
    } else {
        echo json_encode(["message" => "Erreur lors du téléchargement du fichier"]);
    }
} else {
    echo json_encode(["message" => "Données invalides"]);
}
