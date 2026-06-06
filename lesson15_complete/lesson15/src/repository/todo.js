import api from "../services/api.js";

const TodoRepository = {
    async getAll() {
        return await api('/todo');
    },

    async create(description) {
        return await api('/todo', {
            method: 'POST',
            body: JSON.stringify({ description })
        });
    },

    async update(id, data) {
        return await api(`/todo/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    },

    async delete(id) {
        return await api(`/todo/${id}`, {
            method: 'DELETE'
        });
    }
};

export default TodoRepository;
