<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
$pdo = new PDO('mysql:host=localhost;dbname=ebook;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$prenom = trim($_POST['prenom'] ?? '');
$numero = trim($_POST['numero'] ?? '');

// Validation du numéro selon l'indicatif
$patterns = [
    'Congo' => '/^\+242\d{9}$/',
    'France' => '/^\+33\d{9}$/',
    'Cameroun' => '/^\+237\d{9}$/',
    'CoteIvoire' => '/^\+225\d{8}$/'
];

$valid = false;
foreach ($patterns as $pays => $pattern) {
    if (preg_match($pattern, $numero)) {
        $valid = true;
        break;
    }
}

if (!$prenom || !$numero) {
    echo json_encode(['success' => false, 'error' => 'Champs manquants']);
    exit;
}

if (!$valid) {
    echo json_encode(['success' => false, 'error' => 'Numéro invalide. Format attendu : +indicatif suivi du numéro. Exemples : +242XXXXXXXXX, +33XXXXXXXXX, +237XXXXXXXXX, +225XXXXXXXX']);
    exit;
}
// Vérifier si le numéro existe déjà
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE numero = ?");
$stmt->execute([$numero]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Créer le compte
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (prenom, numero) VALUES (?, ?)");
    $stmt->execute([$prenom, $numero]);
    $user_id = $pdo->lastInsertId();
} else {
    $user_id = $user['id'];
}
session_start();
$_SESSION['user_id'] = $user_id;
$_SESSION['prenom'] = $prenom;
echo json_encode(['success' => true, 'prenom' => $prenom, 'user_id' => $user_id]); 