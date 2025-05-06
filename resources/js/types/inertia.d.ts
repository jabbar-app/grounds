// types/inertia.d.ts
import type { PageProps as InertiaPageProps } from '@inertiajs/core'

export interface AuthUser {
    id: number
    name: string
    email?: string
}

export interface PageProps extends InertiaPageProps {
    auth: {
        user: AuthUser | null
    }
    toast?: string
}
