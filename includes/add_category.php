<?php
session_start();
require_once 'db_kurs.php';
header('Content-Type: application/json');

// Проверка авторизации и прав администратора
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'not_admin']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        echo json_encode(['success' => false, 'error' => 'empty']);
        exit();
    }
    // Проверка на дубликаты
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM categories WHERE name = ?');
    $stmt->execute([$name]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'error' => 'exists']);
        exit();
    }
    $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
    $stmt->execute([$name]);
    echo json_encode(['success' => true]);
    exit();
}
echo json_encode(['success' => false, 'error' => 'bad_request']); 