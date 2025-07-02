<?php
session_start();
require_once 'db_kurs.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'not_auth']);
    exit();
}

$user_id = $_SESSION['user_id'];
$amount = floatval($_POST['amount'] ?? 0);
$category_id = intval($_POST['category_id'] ?? 0);
$type = $_POST['type'] ?? '';
$description = trim($_POST['description'] ?? '');

if ($amount <= 0 || !$category_id || !in_array($type, ['income', 'expense'])) {
    echo json_encode(['success' => false, 'error' => 'invalid_data']);
    exit();
}

$stmt = $pdo->prepare('INSERT INTO transactions (user_id, category_id, amount, type, description) VALUES (?, ?, ?, ?, ?)');
$stmt->execute([$user_id, $category_id, $amount, $type, $description]);

echo json_encode(['success' => true]); 