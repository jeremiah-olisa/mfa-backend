export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    flash: {
        message?: string;
        success?: string;
        danger?: string;
    };
};

export interface PaginationProps {
    path: string;
    per_page: number;
    current_page: string | null;
    has_more_pages: boolean | null;
    next_cursor: string | null;
    next_page_url: string | null;
    prev_cursor: string | null;
    prev_page_url: string | null;
    url: string | null;
    with_query_string: string | null;
}

export interface Question {
    id: number;
    question_id: string;
    test_type: string;
    subject: { id: int; label: string };
    question: string;
    answer_id: any;
    created_at: string;
    updated_at: string;
    section: string;
}

export interface UserTable {
    id: number
    name: string
    email: string
    email_verified_at: any
    created_at: string
    updated_at: string
    role: string
    user_app: string
    plan: any
    plan_started_at: any
    plan_expires_at: any
    profile: Profile
}

export interface Profile {
    user_id: number
    phone: string
}
