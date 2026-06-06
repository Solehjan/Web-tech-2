import Auth from "../services/auth.js";
import location from "../services/location.js";
import loading from "../services/loading.js";
import TodoRepository from "../repository/todo.js";

// ---- Render helpers ----

const getTodoList = () => document.querySelector('.todo-list');

const createTodoElement = (todo) => {
    const li = document.createElement('li');
    li.className = 'todo-item';
    li.dataset.id = todo.id;
    if (todo.completed) li.classList.add('todo-item--completed');

    li.innerHTML = `
        <label class="todo-item__label">
            <input
                type="checkbox"
                class="todo-item__checkbox"
                ${todo.completed ? 'checked' : ''}
            >
            <span class="todo-item__text">${escapeHtml(todo.description)}</span>
        </label>
        <button class="btn btn--danger todo-item__delete" aria-label="Удалить">✕</button>
    `;

    // Task 3 & 4*: toggle completed — only update UI after server confirms
    const checkbox = li.querySelector('.todo-item__checkbox');
    checkbox.addEventListener('change', async (e) => {
        // Immediately revert checkbox visually — wait for server response (task 4*)
        checkbox.disabled = true;
        const desiredState = e.target.checked;
        checkbox.checked = !desiredState; // revert until server responds

        const response = await TodoRepository.update(todo.id, { completed: desiredState });

        if (response.ok) {
            checkbox.checked = desiredState;
            todo.completed = desiredState;
            if (desiredState) {
                li.classList.add('todo-item--completed');
            } else {
                li.classList.remove('todo-item--completed');
            }
        }
        checkbox.disabled = false;
    });

    // Task 3: delete todo
    const deleteBtn = li.querySelector('.todo-item__delete');
    deleteBtn.addEventListener('click', async () => {
        deleteBtn.disabled = true;
        const response = await TodoRepository.delete(todo.id);
        if (response.ok) {
            li.remove();
        } else {
            deleteBtn.disabled = false;
        }
    });

    return li;
};

const escapeHtml = (str) => {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
};

// ---- Render page ----

const renderPage = (todos) => {
    const main = document.querySelector('.main');
    main.innerHTML = `
        <section class="todos-section">
            <h1 class="todos-section__title">Мои задачи</h1>

            <form class="todo-form" id="todoForm">
                <div class="text-field">
                    <input
                        type="text"
                        class="text-field__input"
                        id="todoInput"
                        placeholder="Новая задача..."
                        required
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="btn btn--primary">Добавить</button>
            </form>

            <ul class="todo-list" id="todoList"></ul>
        </section>
    `;

    const list = getTodoList();
    todos.forEach(todo => list.appendChild(createTodoElement(todo)));

    // Task 2: add new todo form
    const form = document.getElementById('todoForm');
    const input = document.getElementById('todoInput');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const description = input.value.trim();
        if (!description) return;

        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;

        const response = await TodoRepository.create(description);

        if (response.ok && response.data) {
            list.appendChild(createTodoElement(response.data));
            input.value = '';
        }

        submitBtn.disabled = false;
        input.focus();
    });
};

// ---- Init ----

const init = async () => {
    const { ok: isLogged } = await Auth.me();

    if (!isLogged) {
        return location.login();
    }

    // Task 1: fetch and display all todos
    const response = await TodoRepository.getAll();

    loading.stop();

    if (response.ok) {
        renderPage(response.data || []);
    } else {
        document.querySelector('.main').innerHTML = '<p>Ошибка загрузки задач</p>';
    }
};

if (document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", init);
} else {
    init();
}
