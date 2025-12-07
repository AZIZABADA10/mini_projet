<?php
// Chemin du fichier JSON
$file = __DIR__ . '/../tasks.json'; // ajuste le chemin selon ton projet

// Lire les tâches existantes
$tasks = [];
if (file_exists($file)) {
    $tasks = json_decode(file_get_contents($file), true);
    if (!is_array($tasks)) {
        $tasks = [];
    }
}

// Ajouter une tâche
if (isset($_POST['task']) && !empty($_POST['task'])) {
    $task = [
        "id" => time(), // identifiant unique
        "title" => $_POST['task'],
        "description" => $_POST['description'],
        "priority" => $_POST['priority'] ?? 'Normal',
        "start_date" => $_POST['start_date'],
        "end_date" => $_POST['end_date'],
        "status" => 'todo', // par défaut
        "created_at" => date("Y-m-d H:i")
    ];

    $tasks[] = $task;
    file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}

// Supprimer une tâche
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    foreach ($tasks as $key => $t) {
        if ($t['id'] == $id) {
            unset($tasks[$key]);
        }
    }
    $tasks = array_values($tasks);
    file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT));
    header("Location: index.php");
    exit;
}

$statuses = ['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminée'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Todo List - Kanban</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .task-card { cursor: grab; }
        .fade-in { animation: fadeIn .6s ease-in-out; }
        @keyframes fadeIn { from {opacity:0; transform:translateY(10px);} to {opacity:1; transform:translateY(0);} }
    </style>
</head>
<body class="bg-gray-100 min-h-screen p-4">
    <div class="max-w-5xl mx-auto">

        <!-- Formulaire d'ajout -->
        <div class="bg-white p-6 rounded-xl shadow mb-6 fade-in">
            <h2 class="text-2xl font-bold mb-4">Ajouter une tâche</h2>
            <form method="POST" class="space-y-4">
                <input name="task" type="text" placeholder="Titre de la tâche" class="w-full p-3 border rounded" required>
                <textarea name="description" placeholder="Description" class="w-full p-3 border rounded" required></textarea>
                <input name="priority" type="text" placeholder="Priorité (Facultatif)" class="w-full p-3 border rounded">
                <div class="grid grid-cols-2 gap-4">
                    <input name="start_date" type="date" class="p-3 border rounded" required>
                    <input name="end_date" type="date" class="p-3 border rounded" required>
                </div>
                <button class="w-full bg-purple-600 text-white p-3 rounded hover:bg-purple-700">➕ Ajouter</button>
            </form>
        </div>

        <!-- Kanban -->
        <div class="bg-white p-4 rounded-xl shadow mb-6">
            <h2 class="font-semibold">Mes tâches</h2>
            <p class="text-sm text-gray-500">Glisse-déposez pour changer le statut.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($statuses as $key => $label): ?>
                <div class="bg-white p-4 rounded-xl shadow min-h-[200px]" data-status="<?=$key?>">
                    <h3 class="font-bold mb-3"><?= $label ?></h3>
                    <div class="space-y-3" id="col-<?=$key?>">
                        <?php foreach ($tasks as $task): ?>
                            <?php if ($task['status'] !== $key) continue; ?>
                            <div class="task-card p-3 border rounded" data-id="<?=$task['id']?>">
                                <div class="flex justify-between items-start gap-2">
                                    <div>
                                        <h4 class="font-semibold"><?=htmlspecialchars($task['title'])?></h4>
                                        <p class="text-sm text-gray-600"><?=nl2br(htmlspecialchars($task['description']))?></p>
                                    </div>
                                    <div class="text-right text-xs">
                                        <p><?=htmlspecialchars($task['priority'])?></p>
                                        <p><?=htmlspecialchars($task['end_date'])?></p>
                                    </div>
                                </div>
                                <div class="mt-2 flex gap-2 justify-end">
                                    <a href="edit_task.php?id=<?=$task['id']?>" class="text-sm text-blue-600">Modifier</a>
                                    <a href="?delete=<?=$task['id']?>" class="text-sm text-red-600" onclick="return confirm('Supprimer ?')">Supprimer</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</body>
</html>
