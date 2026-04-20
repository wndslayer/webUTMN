import Auth from "./auth.js";
import config from "./config.js";

const api = async (url, options = {}) => {
    const headers = {
        ...(options.headers || {}),
        "Content-Type": "application/json",
    }

    const token = Auth.token
    if (token) {
        headers["Authorization"] = `Bearer ${token}`
    }

    let response
    try {
        const result = await Promise.all([
            new Promise(resolve => setTimeout(() => resolve(), 200)),
            fetch(config.BASE_URL + url, {
                ...options,
                headers
            })
        ])
        response = result[1]
    } catch (err) {
        console.error('[api] network error', url, err)
        return { ok: false, error: 'network' }
    }

    const text = await response.text()
    let data = null
    if (text) {
        try {
            data = JSON.parse(text)
        } catch (err) {
            console.error('[api] non-JSON response', url, response.status, text)
        }
    }

    if (data && typeof data === 'object' && 'ok' in data) {
        return data
    }

    return {
        ok: response.ok,
        status: response.status,
        data: data
    }
}

export default api
