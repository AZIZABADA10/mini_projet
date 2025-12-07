<?php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json; charset=utf-8');


$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['p'] ?? '';


// simple routing: /api.php?p=tasks or p=tasks/ID
$parts = explode('/', trim($path, '/'));


if ($parts[0] === 'tasks') {
if ($method === 'GET') {
if (isset($parts[1]) && is_numeric($parts[1])) {
$stmt = $pdo->prepare('SELECT * FROM tasks WHERE id = ?');
$stmt->execute([$parts[1]]);
echo json_encode($stmt->fetch());
} else {
$stmt = $pdo->query('SELECT * FROM tasks ORDER BY created_at DESC');
echo json_encode($stmt->fetchAll());
}
} elseif ($method === 'POST') {
$data = json_decode(file_get_contents('php://input'), true);
$stmt = $pdo->prepare('INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)');
$stmt->execute([1, $data['title'] ?? 'Untitled', $data['description'] ?? '']);
echo json_encode(['ok'=>true,'id'=>$pdo->lastInsertId()]);
}
exit;
}


http_response_code(404);
echo json_encode(['error'=>'not found']);