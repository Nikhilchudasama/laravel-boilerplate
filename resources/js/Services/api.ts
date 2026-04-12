import { router } from '@inertiajs/vue3';
import type { AxiosInstance } from 'axios';

// Inertia handles most API calls, but this is useful for custom endpoints
export class ApiService {
    private axios: AxiosInstance;

    constructor(axiosInstance: AxiosInstance) {
        this.axios = axiosInstance;
    }

    async get<T = unknown>(url: string, params?: Record<string, unknown>): Promise<T> {
        const response = await this.axios.get(url, { params });
        return response.data;
    }

    async post<T = unknown>(url: string, data?: unknown): Promise<T> {
        const response = await this.axios.post(url, data);
        return response.data;
    }

    async put<T = unknown>(url: string, data?: unknown): Promise<T> {
        const response = await this.axios.put(url, data);
        return response.data;
    }

    async delete<T = unknown>(url: string): Promise<T> {
        const response = await this.axios.delete(url);
        return response.data;
    }
}

// Helper to use Inertia router for most requests
export const inertiaApi = {
    visit: router.visit,
    get: router.get,
    post: router.post,
    put: router.put,
    patch: router.patch,
    delete: router.delete,
    reload: router.reload,
};
