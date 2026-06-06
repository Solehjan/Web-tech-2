const API_BASE = 'https://jsonplaceholder.typicode.com';

async function fetchPosts() {
    try {
        const response = await fetch(`${API_BASE}/posts`);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        return await response.json();
    } catch (error) {
        console.error('Ошибка:', error);
        throw new Error('Не удалось загрузить список постов');
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function renderPosts(posts) {
    const container = document.getElementById('postsList');
    if (!posts || posts.length === 0) {
        container.innerHTML = '<div class="error">Посты не найдены</div>';
        return;
    }
    container.innerHTML = posts.map(post => `
        <div class="post-card">
            <div class="post-title">${escapeHtml(post.title)}</div>
            <div class="post-body">${escapeHtml(post.body.substring(0, 150))}${post.body.length > 150 ? '...' : ''}</div>
            <a href="post.html?id=${post.id}" class="read-more">Читать далее →</a>
        </div>
    `).join('');
}

async function init() {
    const container = document.getElementById('postsList');
    try {
        const posts = await fetchPosts();
        renderPosts(posts);
    } catch (error) {
        container.innerHTML = `<div class="error"><h3>❌ Ошибка</h3><p>${error.message}</p><button onclick="location.reload()">Повторить</button></div>`;
    }
}

document.addEventListener('DOMContentLoaded', init);