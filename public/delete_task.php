<?php
require_once __DIR__ . '/../config.php';
require_auth();
$user = $_SESSION['user'];
$id = intval($_GET['id'] ?? 0);
if ($id) {
// remove attachment file
$stmt = $pdo->prepare('SELECT attachment FROM tasks WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $user['id']]);
$t = $stmt->fetch();
if ($t && $t['attachment'] && file_exists(__DIR__ . '/' . $t['attachment'])) unlink(__DIR__ . '/' . $t['attachment']);


$pdo->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?')->execute([$id, $user['id']]);
}
redirect('index.php');