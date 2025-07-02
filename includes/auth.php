<?php
session_start();
require_once 'db_kurs.php';

function redirect($url) {
    header('Location: ' . $url);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($action === 'register') {
        $password_confirm = $_POST['password_confirm'] ?? '';
        if ($password !== $password_confirm) {
            $_SESSION['error'] = 'Пароли не совпадают';
            redirect('../pages/register.php');
        }
        if (strlen($username) < 3) {
            $_SESSION['error'] = 'Слишком короткий логин';
            redirect('../pages/register.php');
        }
        if (strlen($password) < 8) {
            $_SESSION['error'] = 'Пароль должен быть не менее 8 символов';
            redirect('../pages/register.php');
        }
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $_SESSION['error'] = 'Пользователь уже существует';
            redirect('../pages/register.php');
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->execute([$username, $hash]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $username;
        redirect('../pages/dashboard.html');
    } elseif ($action === 'login') {
        if ($username === 'admin' && $password === 'admin-root') {
            // Прямой вход для супер-админа
            $_SESSION['user_id'] = 0;
            $_SESSION['username'] = 'admin';
            redirect('../pages/admin.html');
        }
        $stmt = $pdo->prepare('SELECT id, password FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            redirect('../pages/dashboard.html');
        } else {
            $_SESSION['error'] = 'Неверный логин или пароль';
            redirect('../pages/login.php');
        }
    }
}
redirect('../index.html'); 