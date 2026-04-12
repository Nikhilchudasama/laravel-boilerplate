export interface ApiResponse<T = unknown> {
    data: T;
    message?: string;
}

export interface ApiError {
    message: string;
    errors?: Record<string, string[]>;
}

export interface PaginationParams {
    page?: number;
    per_page?: number;
    search_text?: string;
    sort_by?: string;
    sort_direction?: 'asc' | 'desc';
}

export interface PaginationMeta {
    current_page: number;
    from: number | null;
    last_page: number;
    per_page: number;
    to: number | null;
    total: number;
}

export interface PaginatedResponse<T> {
    data: T[];
    meta: PaginationMeta;
    links: {
        first: string | null;
        last: string | null;
        prev: string | null;
        next: string | null;
    };
}
