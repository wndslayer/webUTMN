import Form from "../components/form.js";
import Auth from "../services/auth.js";
import TodoRepository from "../repository/todo.js";
import location from "../services/location.js";
import loading from "../services/loading.js";

const init = async () => {
    const { ok: isLogged } = await Auth.me();

    if (!isLogged) {
        return location.login();
    } else {
        loading.stop();
    }

    const listEl = document.getElementById('todo-list');
    const formEl = document.getElementById('todo-form');

    const renderTodo = (todo) => {
        const li = document.createElement('li');
        li.className = 'todo-item';
        li.dataset.id = todo.id;

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'todo-item__checkbox';
        checkbox.checked = !!todo.completed;

        const text = document.createElement('span');
        text.className = 'todo-item__text';
        text.innerText = todo.description;
        if (todo.completed) text.classList.add('todo-item__text_done');

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'todo-item__remove';
        removeBtn.innerText = '×';

        // Переключение статуса: чекбокс меняется только после успешного ответа
        checkbox.addEventListener('change', async (event) => {
            event.preventDefault();
            const desired = !todo.completed;
            // визуально возвращаем чекбокс в исходное состояние до ответа сервера
            checkbox.checked = todo.completed;
            checkbox.disabled = true;

            const response = await TodoRepository.update(todo.id, { completed: desired });

            checkbox.disabled = false;
            if (response && response.ok) {
                todo.completed = desired;
                checkbox.checked = desired;
                text.classList.toggle('todo-item__text_done', desired);
            }
        });

        removeBtn.addEventListener('click', async () => {
            removeBtn.disabled = true;
            const response = await TodoRepository.remove(todo.id);
            if (response && response.ok) {
                li.remove();
            } else {
                removeBtn.disabled = false;
            }
        });

        li.append(checkbox, text, removeBtn);
        listEl.append(li);
    };

    const renderAll = (todos) => {
        listEl.innerHTML = '';
        todos.forEach(renderTodo);
    };

    const { ok, data } = await TodoRepository.getAll();
    if (ok) renderAll(data.todos || data || []);

    new Form(formEl, {
        'description': (value) => {
            if (!value || value.trim().length === 0) {
                return 'Описание не может быть пустым';
            }
            return false;
        }
    }, async (values) => {
        const response = await TodoRepository.create(values);
        if (response && response.ok) {
            const newTodo = response.data.todo || response.data;
            renderTodo(newTodo);
            formEl.reset();
        }
    });
};

if (document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", init);
} else {
    init();
}
