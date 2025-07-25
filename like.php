<?php
header('Content-Type: application/json');
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=ebook;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'error' => 'Non connecté']);
    exit;
}

// Vérifier si l'utilisateur a déjà liké
$stmt = $pdo->prepare('SELECT 1 FROM likes WHERE user_id = ?');
$stmt->execute([$user_id]);
if ($stmt->fetch()) {
    // Déjà liké
    $stmt = $pdo->query('SELECT count FROM likes_count WHERE id = 1');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success' => false, 'error' => 'Vous avez déjà liké.', 'count' => $row['count'] ?? 0]);
    exit;
}

// Ajouter le like
$stmt = $pdo->prepare('INSERT INTO likes (user_id) VALUES (?)');
$stmt->execute([$user_id]);

// Incrémenter le compteur global
$pdo->query('UPDATE likes_count SET count = count + 1 WHERE id = 1');
$stmt = $pdo->query('SELECT count FROM likes_count WHERE id = 1');
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode(['success' => true, 'count' => $row['count'] ?? 0]);
//
// Structure SQL à utiliser :
//
// CREATE TABLE likes (
//   id INT AUTO_INCREMENT PRIMARY KEY,
//   user_id INT NOT NULL UNIQUE
// );
//
// CREATE TABLE likes_count (
//   id INT PRIMARY KEY,
//   count INT NOT NULL DEFAULT 0
// );
// INSERT INTO likes_count (id, count) VALUES (1, 0) ON DUPLICATE KEY UPDATE id=id; 