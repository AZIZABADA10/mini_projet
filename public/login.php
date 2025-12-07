<?php
require_once __DIR__ . '/../config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$password = $_POST['password'];


if (!$email || empty($password)) $error = 'Identifiants invalides.';
else {
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();
if ($user && password_verify($password, $user['password'])) {
unset($user['password']);
$_SESSION['user'] = $user;
redirect('index.php');
} else $error = 'Email ou mot de passe incorrect.';
}
}
?>


<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Connexion</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-600 to-indigo-700 min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-md bg-white p-6 rounded-xl shadow-lg">
<h1 class="text-2xl font-bold text-center text-purple-700 mb-4">Connexion</h1>
<?php if (!empty($error)): ?>
<div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?=htmlspecialchars($error)?></div>
<?php endif; ?>
<form method="post" class="space-y-4">
<input name="email" placeholder="Email" type="email" class="w-full p-3 border rounded" required>
<input name="password" placeholder="Mot de passe" type="password" class="w-full p-3 border rounded" required>
<button class="w-full bg-purple-600 text-white p-3 rounded">Se connecter</button>
<p class="text-sm text-center">Pas encore inscrit ? <a href="register.php" class="text-purple-600">Créer un compte</a></p>
</form>
</div>
</body>
</html>