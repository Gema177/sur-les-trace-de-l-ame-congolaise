<?php
header('Content-Type: application/json');
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=ebook;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$user_id = $_SESSION['user_id'] ?? null;

// Récupérer le nombre total de likes
$stmt = $pdo->query('SELECT count FROM likes_count WHERE id = 1');
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$count = $row['count'] ?? 0;

// Vérifier si l'utilisateur a déjà liké
$has_liked = false;
if ($user_id) {
    $stmt = $pdo->prepare('SELECT 1 FROM likes WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $has_liked = (bool)$stmt->fetch();
}

echo json_encode([
    'count' => $count,
    'has_liked' => $has_liked
]); 