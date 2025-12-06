<?php
// Chemin du fichier JSON
$file = 'tasks.json';

// Lire les tâches existantes
$tasks = [];
if (file_exists($file)) {
    $tasks = json_decode(file_get_contents($file), true);
}

// Ajouter une tâche
if (isset($_POST['task']) && !empty($_POST['task'])) {
    $tasks[] = ["text" => $_POST['task']];
    file_put_contents($file, json_encode($tasks));
    header("Location: index.php");
}

// Supprimer une tâche
if (isset($_GET['delete'])) {
    $index = $_GET['delete'];
    unset($tasks[$index]);
    $tasks = array_values($tasks);
    file_put_contents($file, json_encode($tasks));
    header("Location: index.php");
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

    <div class="max-w-xl mx-auto bg-white shadow-2xl p-6 rounded-xl">
        <h1 class="text-3xl font-bold text-purple-600 mb-4 text-center"> Mini Projet Todo List</h1>

        <form method="POST" class="flex gap-3 mb-6">
            <input name="task" type="text" placeholder="Ajouter une tâche" class="flex-1 p-2 border rounded-lg" required>
            <button class="bg-purple-600 text-white px-4 py-2 rounded-lg">Ajouter</button>
        </form>

        <ul class="space-y-3">
            <?php foreach ($tasks as $index => $t): ?>
                <li class="flex justify-between items-center bg-gray-200 p-3 rounded-lg">
                    <span><?= htmlspecialchars($t['text']) ?></span>
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
