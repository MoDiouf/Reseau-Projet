<?php
// config.php - Configuration de la base de données
$host = 'localhost';
$dbname = 'smarttech_db';
$username = 'root';
$password = 'Sword@rtonline';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// employees.php - API de gestion des employés
header('Content-Type: application/json');
require 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $pdo->query("SELECT * FROM employees");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO employees (name, email, position) VALUES (?, ?, ?)");
        $stmt->execute([$data['name'], $data['email'], $data['position']]);
        echo json_encode(["message" => "Employé ajouté avec succès"]);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE employees SET name=?, email=?, position=? WHERE id=?");
        $stmt->execute([$data['name'], $data['email'], $data['position'], $data['id']]);
        echo json_encode(["message" => "Employé mis à jour"]);
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("DELETE FROM employees WHERE id=?");
        $stmt->execute([$data['id']]);
        echo json_encode(["message" => "Employé supprimé"]);
        break;
    default:
        echo json_encode(["message" => "Méthode non autorisée"]);
        break;
}
