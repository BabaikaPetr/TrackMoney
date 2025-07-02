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
    $category_id = intval($_POST['category_id'] ?? 0);

    if ($category_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'invalid_id']);
        exit();
    }

    try {
        // Начинаем транзакцию для обеспечения атомарности операций
        $pdo->beginTransaction();

        // Сначала удаляем все транзакции, связанные с этой категорией
        $stmt_delete_transactions = $pdo->prepare('DELETE FROM transactions WHERE category_id = ?');
        $stmt_delete_transactions->execute([$category_id]);

        // Затем удаляем саму категорию
        $stmt_delete_category = $pdo->prepare('DELETE FROM categories WHERE id = ?');
        $stmt_delete_category->execute([$category_id]);

        // Проверяем, была ли удалена категория
        if ($stmt_delete_category->rowCount() > 0) {
            $pdo->commit(); // Подтверждаем транзакцию
            echo json_encode(['success' => true]);
        } else {
            // Категория с таким ID не найдена, откатываем транзакцию
            $pdo->rollBack();
            echo json_encode(['success' => false, 'error' => 'not_found']);
        }

    } catch (PDOException $e) {
        // В случае ошибки базы данных, откатываем транзакцию и возвращаем ошибку
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => 'db_error', 'message' => $e->getMessage()]);
    }
    exit();
}

echo json_encode(['success' => false, 'error' => 'bad_request']);
?> 