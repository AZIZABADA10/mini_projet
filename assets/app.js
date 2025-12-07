document.addEventListener('DOMContentLoaded', () => {
    const cols = document.querySelectorAll('[data-status]');
    const draggables = document.querySelectorAll('.task-card');


    draggables.forEach(d => {
        d.draggable = true;
        d.addEventListener('dragstart', e => {
            d.classList.add('opacity-50');
            e.dataTransfer.setData('text/plain', d.dataset.id);
        });
        d.addEventListener('dragend', () => d.classList.remove('opacity-50'));
    });


    cols.forEach(col => {
        col.addEventListener('dragover', e => e.preventDefault());
        col.addEventListener('drop', async e => {
            e.preventDefault();
            const id = e.dataTransfer.getData('text/plain');
            const status = col.dataset.status;
            // move in DOM
            const card = document.querySelector('.task-card[data-id="' + id + '"]');
            if (card) {
                col.querySelector('.space-y-3').appendChild(card);
            }
            // send request to update status
            try {
                await fetch('/public/api.php?p=tasks/' + id, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'update_status', status })
                });
            } catch (err) { console.error(err); }
        });
    });
});