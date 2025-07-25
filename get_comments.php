<?php
header('Content-Type: application/json');
$pdo = new PDO('mysql:host=localhost;dbname=ebook;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$stmt = $pdo->query('SELECT u.prenom, c.message, c.date FROM commentaires c JOIN utilisateurs u ON c.user_id = u.id ORDER BY c.date DESC');
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)); 