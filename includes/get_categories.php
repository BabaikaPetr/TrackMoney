<?php
require_once 'db_kurs.php';
header('Content-Type: application/json');

$stmt = $pdo->query('SELECT id, name FROM categories ORDER BY name');
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($categories); 