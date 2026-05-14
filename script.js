const root = document.querySelector('#list-items');

if (root) {
    root.addEventListener('click', (e) => {
        const toggleBtn = e.target.closest('[data-toggle]');
        if (!toggleBtn) return;

        const item = toggleBtn.closest('.list-item[data-parent]');
        if (!item) return;

        item.classList.toggle('list-item_open');
    });
}
