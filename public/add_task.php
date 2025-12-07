<?php
require_once __DIR__ . '/../config.php';
require_auth();
$user = $_SESSION['user'];


if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');


$title = trim($_POST['title']);
$description = trim($_POST['description']);
$start_date = $_POST['start_date'] ?: null;
$end_date = $_POST['end_date'] ?: null;
$priority = in_array($_POST['priority'], ['low','medium','high']) ? $_POST['priority'] : 'medium';
$status = in_array($_POST['status'], ['todo','in_progress','done']) ? $_POST['status'] : 'todo';
$tags = isset($_POST['tags']) ? (array)$_POST['tags'] : [];


$attachmentPath = null;
if (!empty($_FILES['attachment']['name'])) {
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
$f = $_FILES['attachment'];
$ext = pathinfo($f['name'], PATHINFO_EXTENSION);
$allowed = ['png','jpg','jpeg','gif','pdf'];
if (!in_array(strtolower($ext), $allowed)) {
$_SESSION['flash_error'] = 'Type de fichier non autorisé.';
redirect('index.php');
}
$newName = uniqid('att_') . '.' . $ext;
move_uploaded_file($f['tmp_name'], $uploadDir . $newName);
$attachmentPath = 'uploads/' . $newName;
}


$stmt = $pdo->prepare('INSERT INTO tasks (user_id, title, description, start_date, end_date, priority, status, attachment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
$stmt->execute([$user['id'], $title, $description, $start_date, $end_date, $priority, $status, $attachmentPath]);
$taskId = $pdo->lastInsertId();


// tags relation
if (!empty($tags)) {
$ins = $pdo->prepare('INSERT IGNORE INTO task_tags (task_id, tag_id) VALUES (?, ?)');
foreach ($tags as $tid) {
$ins->execute([$taskId, $tid]);
}
}


redirect('index.php');