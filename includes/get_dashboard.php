<?php
session_start();
require_once 'db_kurs.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'not_auth']);
    exit();
}
$user_id = $_SESSION['user_id'];

// Баланс
$stmt = $pdo->prepare('SELECT 
    SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income,
    SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense
    FROM transactions WHERE user_id = ?');
$stmt->execute([$user_id]);
$row = $stmt->fetch();
$balance = floatval($row['income']) - floatval($row['expense']);

// История операций (последние 20)
$stmt = $pdo->prepare('SELECT t.id, t.created_at, c.name as category, t.description, t.amount, t.type
    FROM transactions t
    JOIN categories c ON t.category_id = c.id
    WHERE t.user_id = ?
    ORDER BY t.created_at DESC
    LIMIT 20');
$stmt->execute([$user_id]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Данные для графика (суммы по категориям расходов)
$stmt = $pdo->prepare('SELECT c.name, SUM(t.amount) as total
    FROM transactions t
    JOIN categories c ON t.category_id = c.id
    WHERE t.user_id = ? AND t.type = "expense"
    GROUP BY t.category_id
    ORDER BY total DESC');
$stmt->execute([$user_id]);
$chart = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'balance' => $balance,
    'history' => $history,
    'chart' => $chart,
    'username' => $_SESSION['username'] ?? ''
]); 