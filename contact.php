<?php
header('Content-Type: application/json');
$pdo = new PDO('mysql:host=localhost;dbname=ebook;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
session_start();
$nom = htmlspecialchars(trim($_POST['nom'] ?? ($_SESSION['prenom'] ?? '')));
$email = htmlspecialchars(trim($_POST['email'] ?? ''));
$message = htmlspecialchars(trim($_POST['message'] ?? ''));
if(trim($nom) && filter_var($email, FILTER_VALIDATE_EMAIL) && trim($message)) {
    $stmt = $pdo->prepare('INSERT INTO contact (nom, email, message, date) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$nom, $email, $message]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Champs invalides']);
} 