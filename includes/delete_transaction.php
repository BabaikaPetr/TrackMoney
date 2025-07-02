<?php
session_start();
require_once 'db_kurs.php';
header('Content-Type: application/json');

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'not_auth']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_id = intval($_POST['transaction_id'] ?? 0);

    if ($transaction_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'invalid_id']);
        exit();
    }

    try {
        // Удаляем транзакцию, проверяя, что она принадлежит текущему пользователю
        $stmt = $pdo->prepare('DELETE FROM transactions WHERE id = ? AND user_id = ?');
        $stmt->execute([$transaction_id, $user_id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            // Транзакция с таким ID не найдена или не принадлежит пользователю
            echo json_encode(['success' => false, 'error' => 'not_found']);
        }

    } catch (PDOException $e) {
        // В случае ошибки базы данных
        echo json_encode(['success' => false, 'error' => 'db_error', 'message' => $e->getMessage()]);
    }
    exit();
}

echo json_encode(['success' => false, 'error' => 'bad_request']);
?> 