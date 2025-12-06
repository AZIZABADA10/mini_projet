<?php
// Chemin du fichier JSON
$file = 'tasks.json';

// Lire les tâches existantes
$tasks = [];
if (file_exists($file)) {
    $tasks = json_decode(file_get_contents($file), true);
}

// Ajouter une tâche
if (
    isset($_POST['task']) && !empty($_POST['task']) &&
    isset($_POST['description']) && !empty($_POST['description']) &&
    isset($_POST['date_debut']) && isset($_POST['date_fin'])
) {
    $tasks[] = [
        "text" => $_POST['task'],
        "description" => $_POST['description'],
        "date_debut" => $_POST['date_debut'],
        "date_fin" => $_POST['date_fin']
    ];

    file_put_contents($file, json_encode($tasks));
    header("Location: index.php");
    exit;
}

// Supprimer une tâche
if (isset($_GET['delete'])) {
    $index = $_GET['delete'];
    unset($tasks[$index]);
    $tasks = array_values($tasks);
    file_put_contents($file, json_encode($tasks));
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Mini Projet PHP - Todo List</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

<div class="max-w-2xl mx-auto bg-white shadow-2xl p-6 rounded-xl">
<h1 class="text-3xl font-bold text-purple-600 mb-4 text-center">📌 Mini Projet Todo List Avancé</h1>

<form method="POST" class="space-y-4 mb-6">

    <input name="task" type="text" placeholder="Nom de la tâche" class="w-full p-2 border rounded-lg" required>

    <textarea name="description" placeholder="Description" class="w-full p-2 border rounded-lg" required></textarea>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-sm text-gray-600">Date début</label>
            <input type="date" name="date_debut" class="w-full p-2 border rounded-lg" required>
        </div>
        <div>
            <label class="text-sm text-gray-600">Date fin</label>
            <input type="date" name="date_fin" class="w-full p-2 border rounded-lg" required>
        </div>
    </div>

    <button class="bg-purple-600 text-white px-4 py-2 rounded-lg w-full">Ajouter</button>
</form>

<ul class="space-y-3">
<?php foreach ($tasks as $index => $t): ?>
    <li class="bg-gray-100 p-4 rounded-lg shadow flex justify-between items-start">
        <div>
            <h2 class="font-bold text-lg text-purple-700"><?= htmlspecialchars($t['text']) ?></h2>
            <p class="text-gray-700"><?= htmlspecialchars($t['description']) ?></p>
            <p class="text-sm text-gray-500 mt-1">📅 Début : <?= $t['date_debut'] ?></p>
            <p class="text-sm text-gray-500">⏳ Fin : <?= $t['date_fin'] ?></p>
        </div>

        <a href="?delete=<?= $index ?>" class="text-red-500 font-bold">Supprimer</a>
    </li>
<?php endforeach; ?>
</ul>
</div>

<script>
const links = document.querySelectorAll('a[href*="delete"]');
links.forEach(link => {
    link.addEventListener('click', function(e) {
        if (!confirm("Voulez-vous supprimer cette tâche ?")) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>
