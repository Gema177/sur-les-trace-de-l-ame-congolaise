<?php
header('Content-Type: application/json');
$pdo = new PDO('mysql:host=localhost;dbname=ebook;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'error' => 'Non connecté']);
    exit;
}
$message = htmlspecialchars(trim($_POST['message'] ?? ''));
if(trim($message)) {
    $stmt = $pdo->prepare('INSERT INTO commentaires (user_id, message, date) VALUES (?, ?, NOW())');
    $stmt->execute([$user_id, $message]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Message manquant']);
} 