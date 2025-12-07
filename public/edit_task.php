<?php
<div class="w-full max-w-2xl bg-white p-6 rounded-xl shadow">
<h1 class="text-xl font-bold mb-4">Modifier la tâche</h1>
<form method="post" enctype="multipart/form-data" class="space-y-3">
<input name="title" value="<?=htmlspecialchars($task['title'])?>" class="w-full p-3 border rounded" required>
<textarea name="description" rows="4" class="w-full p-3 border rounded"><?=htmlspecialchars($task['description'])?></textarea>
<div class="grid grid-cols-2 gap-2">
<input name="start_date" type="date" value="<?=$task['start_date']?>" class="p-2 border rounded">
<input name="end_date" type="date" value="<?=$task['end_date']?>" class="p-2 border rounded">
</div>
<select name="priority" class="w-full p-2 border rounded">
<option value="low" <?= $task['priority']==='low' ? 'selected' : '' ?>>Low</option>
<option value="medium" <?= $task['priority']==='medium' ? 'selected' : '' ?>>Medium</option>
<option value="high" <?= $task['priority']==='high' ? 'selected' : '' ?>>High</option>
</select>
<select name="status" class="w-full p-2 border rounded">
<option value="todo" <?= $task['status']==='todo' ? 'selected' : '' ?>>À faire</option>
<option value="in_progress" <?= $task['status']==='in_progress' ? 'selected' : '' ?>>En cours</option>
<option value="done" <?= $task['status']==='done' ? 'selected' : '' ?>>Terminée</option>
</select>


<label class="block text-sm">Tags</label>
<select name="tags[]" multiple class="w-full p-2 border rounded">
<?php foreach ($tags as $t): ?>
<option value="<?=$t['id']?>" <?= in_array($t['id'], $selectedTags) ? 'selected' : '' ?>><?=htmlspecialchars($t['name'])?></option>
<?php endforeach; ?>
</select>


<div>
<label class="text-sm">Pièce jointe actuelle :</label>
<?php if ($task['attachment']): ?>
<div class="p-2 border rounded bg-gray-50"> <a href="<?=$task['attachment']?>" target="_blank">Voir</a> </div>
<?php else: ?>
<div class="text-sm text-gray-500">Aucune</div>
<?php endif; ?>
</div>


<input type="file" name="attachment" accept="image/*,.pdf" class="w-full">


<div class="flex gap-2">
<button class="bg-purple-600 text-white p-2 rounded">Enregistrer</button>
<a href="index.php" class="p-2 border rounded">Annuler</a>
</div>
</form>
</div>
</body>
</html>