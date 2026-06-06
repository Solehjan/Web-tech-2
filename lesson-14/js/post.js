const API_BASE = 'https://jsonplaceholder.typicode.com';

function getPostIdFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    if (!id) throw new Error('ID поста не указан');
    const postId = parseInt(id);
    if (isNaN(postId) || postId <= 0) throw new Error('Некорректный ID');
    return postId;
}

async function fetchPost(postId) {
    try {
        const response = await fetch(`${API_BASE}/posts/${postId}`);
        if (!response.ok) {
            if (response.status === 404) throw new Error(`Пост ${postId} не найден`);
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        throw new Error(`Не удалось загрузить пост: ${error.message}`);
    }
}

async function fetchComments(postId) {
    try {
        const response = await fetch(`${API_BASE}/posts/${postId}/comments`);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return await response.json();
    } catch (error) {
        throw new Error(`Не удалось загрузить комментарии: ${error.message}`);
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function renderPost(post) {
    const container = document.getElementById('postDetail');
    container.innerHTML = `
        <h1>${escapeHtml(post.title)}</h1>
        <div class="post-body">${escapeHtml(post.body)}</div>
        <hr><small>Пост #${post.id}</small>
    `;
}

function renderComments(comments) {
    const container = document.getElementById('commentsList');
    if (!comments || comments.length === 0) {
        container.innerHTML = '<div class="loading">Комментариев пока нет</div>';
        return;
    }
    container.innerHTML = comments.map(comment => `
        <div class="comment-card">
            <div class="comment-name">${escapeHtml(comment.name)}</div>
            <div class="comment-email">📧 ${escapeHtml(comment.email)}</div>
            <div class="comment-body">${escapeHtml(comment.body)}</div>
        </div>
    `).join('');
}

function showError(message) {
    const container = document.getElementById('postDetail');
    container.innerHTML = `<div class="error"><h3>❌ Ошибка</h3><p>${escapeHtml(message)}</p><button onclick="location.reload()">Повторить</button><button onclick="window.location.href='index.html'">Назад</button></div>`;
    document.getElementById('commentsList').innerHTML = '';
}

async function initPostPage() {
    try {
        const postId = getPostIdFromURL();
        const [post, comments] = await Promise.all([fetchPost(postId), fetchComments(postId)]);
        renderPost(post);
        renderComments(comments);
        document.title = `${post.title} | Пост`;
    } catch (error) {
        showError(error.message);
    }
}

document.addEventListener('DOMContentLoaded', initPostPage);