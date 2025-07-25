<?php
header('Content-Type: application/json');
$pdo = new PDO('mysql:host=localhost;dbname=ebook;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$email = htmlspecialchars(trim($_POST['email'] ?? ''));
if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $stmt = $pdo->prepare('INSERT INTO newsletter (email, date) VALUES (?, NOW())');
    $stmt->execute([$email]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Email invalide']);
} 