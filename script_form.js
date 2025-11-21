document.addEventListener('DOMContentLoaded', () => {
    const addBtn = document.getElementById('add-collapse-btn');
    const formsContainer = document.getElementById('collapse-forms-container'); 
    const saveBtn = document.getElementById('save-data-btn');
    const statusEl = document.getElementById('save-status');
    let collapseCount = 0;

    //створення однієї великої форми Collapse
    const createCollapseForm = () => {
        collapseCount++;
        const id = `item-${collapseCount}`;

        const formHtml = `
            <div class="collapse-item-form large" data-id="${id}">
                <div class="form-header">
                    <label>Об\'єкт Collapse ${collapseCount}</label>
                    <button type="button" class="remove-btn" data-id="${id}">Видалити</button>
                </div>

                <label for="title-${id}">Заголовок:</label>
                <input type="text" id="title-${id}" class="title-input wide-input" name="title[]" placeholder="Введіть заголовок" required>
                
                <label for="content-${id}">Контент (HTML):</label>
                <textarea id="content-${id}" class="content-textarea wide-input" name="content[]" placeholder="Введіть контент (HTML-код)" rows="6" required></textarea>
            </div>
        `;
        formsContainer.insertAdjacentHTML('beforeend', formHtml);
    };

    addBtn.addEventListener('click', createCollapseForm);

    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-btn')) {
            e.target.closest('.collapse-item-form').remove();
        }
    });

    createCollapseForm(); 

    //збереження даних (AJAX)
    saveBtn.addEventListener('click', async () => {
        const forms = formsContainer.querySelectorAll('.collapse-item-form');
        const collapseItems = [];

        forms.forEach(form => {
            const titleInput = form.querySelector('.title-input');
            const contentInput = form.querySelector('.content-textarea');

            const title = titleInput ? titleInput.value.trim() : '';
            const content = contentInput ? contentInput.value.trim() : '';

            if (title && content) {
                collapseItems.push({
                    title: title,
                    content: content
                });
            }
        });

        if (collapseItems.length === 0) {
            statusEl.textContent = 'Будь ласка, додайте хоча б один повний об\'єкт Collapse.';
            statusEl.style.color = 'orange';
            return;
        }

        statusEl.textContent = 'Збереження...';
        statusEl.style.color = 'darkorange';

        try {
            const response = await fetch('save_data.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ collapseItems: collapseItems })
            });

            const result = await response.json();

            if (result.success) {
                statusEl.textContent = result.message;
                statusEl.style.color = 'green';
            } else {
                statusEl.textContent = 'Помилка збереження: ' + result.message;
                statusEl.style.color = 'red';
            }

        } catch (error) {
            console.error('Помилка Fetch:', error);
            statusEl.textContent = 'Критична помилка мережі чи сервера.';
            statusEl.style.color = 'red';
        }
    });
});