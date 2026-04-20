const API_URL = 'https://jsonplaceholder.typicode.com'

const getPost = async (id) => {
    try {
        const res = await fetch(`${API_URL}/posts/${id}`)

        if (!res.ok) {
            throw new Error(`Ошибка загрузки поста: ${res.status} ${res.statusText}`)
        }

        return await res.json()
    } catch (error) {
        console.error('Ошибка в getPost:', error)
        throw error
    }
}

const getComments = async (postId) => {
    try {
        const res = await fetch(`${API_URL}/posts/${postId}/comments`)

        if (!res.ok) {
            throw new Error(`Ошибка загрузки комментариев: ${res.status} ${res.statusText}`)
        }

        return await res.json()
    } catch (error) {
        console.error('Ошибка в getComments:', error)
        throw error
    }
}

const escapeHtml = (str = '') => String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;')

const renderPost = (post) => `
    <article class="post-detail">
        <h1 class="post-detail__title">${escapeHtml(post.title)}</h1>
        <p class="post-detail__body">${escapeHtml(post.body)}</p>
        <div class="post-detail__meta">
            Пост #${post.id} · Пользователь #${post.userId}
        </div>
    </article>
`

const renderComments = (comments) => {
    if (!comments.length) {
        return `<p>Комментариев пока нет.</p>`
    }

    return comments.map(comment => `
        <div class="comment">
            <div class="comment__header">
                <span class="comment__name">${escapeHtml(comment.name)}</span>
                <span class="comment__email">${escapeHtml(comment.email)}</span>
            </div>
            <p class="comment__body">${escapeHtml(comment.body)}</p>
        </div>
    `).join('')
}

const renderError = (el, message) => {
    el.innerHTML = `<div class="post-page__error">${escapeHtml(message)}</div>`
}

const init = async () => {
    const contentEl = document.querySelector('[data-post-content]')
    const commentsEl = document.querySelector('[data-post-comments]')

    const url = new URL(window.location.href)
    const id = url.searchParams.get('id')

    if (!id) {
        renderError(contentEl, 'Не указан id поста в URL (ожидается ?id=...)')
        commentsEl.innerHTML = ''
        return
    }

    // Загружаем пост и комментарии параллельно — быстрее и каждый запрос со своей обработкой ошибок
    const [postResult, commentsResult] = await Promise.allSettled([
        getPost(id),
        getComments(id),
    ])

    if (postResult.status === 'fulfilled') {
        contentEl.innerHTML = renderPost(postResult.value)
        document.title = postResult.value.title
    } else {
        renderError(contentEl, `Не удалось загрузить пост. ${postResult.reason?.message ?? ''}`)
    }

    if (commentsResult.status === 'fulfilled') {
        commentsEl.innerHTML = renderComments(commentsResult.value)
    } else {
        renderError(commentsEl, `Не удалось загрузить комментарии. ${commentsResult.reason?.message ?? ''}`)
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init)
} else {
    init()
}
