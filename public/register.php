<?php
require_once __DIR__ . '/../config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$name = trim($_POST['name']);
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$password = $_POST['password'];
$confirm = $_POST['confirm'];


if (!$email) $error = 'Email invalide.';
elseif (empty($name) || empty($password)) $error = 'Remplir tous les champs.';
elseif ($password !== $confirm) $error = 'Les mots de passe ne correspondent pas.';
else {
// check exist
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) $error = 'Email déjà utilisé.';
else {
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
$stmt->execute([$name, $email, $hash]);
redirect('login.php');
}
}
}
?>


<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Inscription</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-600 to-indigo-700 min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-md bg-white p-6 rounded-xl shadow-lg">
<h1 class="text-2xl font-bold text-center text-purple-700 mb-4">Créer un compte</h1>
<?php if (!empty($error)): ?>
<div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?=htmlspecialchars($error)?></div>
<?php endif; ?>
<form method="post" class="space-y-4">
<input name="name" placeholder="Nom" class="w-full p-3 border rounded" required>
<input name="email" placeholder="Email" type="email" class="w-full p-3 border rounded" required>
<input name="password" placeholder="Mot de passe" type="password" class="w-full p-3 border rounded" required>
<input name="confirm" placeholder="Confirmer mot de passe" type="password" class="w-full p-3 border rounded" required>
<button class="w-full bg-purple-600 text-white p-3 rounded">S'inscrire</button>
<p class="text-sm text-center">Déjà inscrit ? <a href="login.php" class="text-purple-600">Connexion</a></p>
</form>
</div>
</body>
</html>