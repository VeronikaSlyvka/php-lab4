    //додавання розгортання і згортання
    function bindCollapseEvents() {
        const headers = document.querySelectorAll('.collapse-header');

        headers.forEach(header => {
            if (header.classList.contains('bound')) return;
            header.classList.add('bound'); 

            header.addEventListener('click', (event) => {
                const targetSelector = header.getAttribute('data-target');
                const targetBody = document.querySelector(targetSelector);

                if (targetBody) {
                    document.querySelectorAll('.collapse-header.active').forEach(h => {
                        const otherTargetSelector = h.getAttribute('data-target');
                        const otherTargetBody = document.querySelector(otherTargetSelector);

                        if (otherTargetBody && otherTargetBody !== targetBody) {
                            otherTargetBody.classList.remove('show');
                            h.classList.remove('active');
                        }
                    });

                    targetBody.classList.toggle('show');
                    header.classList.toggle('active');
                }
            });
        });
    }

    //для асинхронного отримання оновлень
    function fetchUpdates() {
        const displayContainer = document.getElementById('collapse-display');
        const updateStatus = document.getElementById('update-status');

        if (!displayContainer || !updateStatus) {
            console.error('Не знайдено контейнери #collapse-display або #update-status');
            return;
        }

        ///AJAX-запит до файлу
        fetch('fetch_updates.php')
            .then(response => response.text())
            .then(newHtml => {
                const cleanedNewHtml = newHtml.trim();
                const cleanedCurrentHtml = displayContainer.innerHTML.trim();

                if (cleanedNewHtml !== cleanedCurrentHtml) {
                    displayContainer.innerHTML = newHtml;
                    
                    bindCollapseEvents(); 

                    updateStatus.textContent = 'Оновлено: Знайдено нові дані Collapse.';
                    updateStatus.style.color = 'green';
                } else {
                    updateStatus.textContent = 'Очікування оновлень... Дані актуальні.';
                    updateStatus.style.color = 'gray';
                }
            })
            .catch(error => {
                console.error('Помилка під час отримання оновлень:', error);
                updateStatus.textContent = 'Помилка завантаження даних.';
                updateStatus.style.color = 'red';
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        bindCollapseEvents(); 
        setInterval(fetchUpdates, 5000); 
    });