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

    $task = [
        "title" => $_POST['task'],
        "description" => $_POST['description'],
        "start_date" => $_POST['start_date'],
        "end_date" => $_POST['end_date'],
        "created_at" => date("Y-m-d H:i")
    ];

    $tasks[] = $task;

    file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}

// Supprimer une tâche
if (isset($_GET['delete'])) {
    $index = $_GET['delete'];
    unset($tasks[$index]);
    $tasks = array_values($tasks);
    file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Todo List - Projet PHP</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .fade-in {
            animation: fadeIn .6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-purple-600 to-indigo-700 min-h-screen p-4 sm:p-10">

    <div class="max-w-3xl mx-auto bg-white shadow-2xl p-8 rounded-[20px] fade-in">

        <h1 class="text-4xl font-extrabold text-purple-700 mb-8 text-center tracking-wide">
            ✨ Mini Projet Todo List
        </h1>

        <!-- Formulaire -->
        <form method="POST" class="space-y-5 bg-purple-50 p-6 rounded-xl shadow">

            <div>
                <label class="font-semibold text-gray-700">Titre de la tâche :</label>
                <input 
                    name="task" 
                    type="text" 
                    placeholder="Ex : Réviser PHP" 
                    class="w-full p-3 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition" 
                    required>
            </div>

            <div>
                <label class="font-semibold text-gray-700">Description :</label>
                <textarea 
                    name="description"
                    placeholder="Décrire la tâche..." 
                    class="w-full p-3 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition" 
                    rows="3"
                    required></textarea>
            </div>

            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="font-semibold text-gray-700">Date début :</label>
                    <input 
                        name="start_date" 
                        type="date" 
                        class="w-full p-3 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition" 
                        required>
                </div>

                <div>
                    <label class="font-semibold text-gray-700">Date fin :</label>
                    <input 
                        name="end_date" 
                        type="date" 
                        class="w-full p-3 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 transition" 
                        required>
                </div>
            </div>

            <button class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg text-lg font-semibold shadow-lg transition">
                ➕ Ajouter une tâche
            </button>
        </form>

        <!-- Liste des tâches -->
        <h2 class="text-2xl font-bold text-gray-800 mt-10 mb-4">📋 Liste des tâches</h2>

        <ul class="space-y-5">
            <?php foreach ($tasks as $index => $t): ?>
                <li class="bg-gray-100 p-5 rounded-xl shadow flex flex-col sm:flex-row justify-between items-start gap-4 fade-in">

                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-purple-700 mb-1">
                            <?= htmlspecialchars($t["title"]) ?>
                        </h3>

                        <p class="text-gray-700 mb-3">
                            <?= nl2br(htmlspecialchars($t["description"])) ?>
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-600">
                            <p>📅 <strong>Début :</strong> <?= $t["start_date"] ?></p>
                            <p>⏳ <strong>Fin :</strong> <?= $t["end_date"] ?></p>
                            <p>🕒 <strong>Ajoutée le :</strong> <?= $t["created_at"] ?></p>
                        </div>
                    </div>

                    <a href="?delete=<?= $index ?>" 
                       class="text-red-600 font-bold px-4 py-2 bg-red-100 rounded-lg hover:bg-red-200 transition shadow">
                        🗑 Supprimer
                    </a>

                </li>
            <?php endforeach; ?>
        </ul>
    </div>

<script>
    const links = document.querySelectorAll('a[href*="delete"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm("❗ Voulez-vous vraiment supprimer cette tâche ?")) {
                e.preventDefault();
            }
        });
    });
</script>

</body>
</html>
