<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

defineProps({
    user: {
        type: Object
    },
});

// Example utility function for formatting (if needed inline):
const formatDateTime = (dateString) => {
    if (!dateString) return 'N/A'; // Handle null or empty dates
    return new Intl.DateTimeFormat('en-US', {
        year: 'numeric',
        month: 'short',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    }).format(new Date(dateString));
}
</script>

<template>

    <Head :title="user.username" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Show User {{ user.username }}
            </h2>
            <Link :href="route('user.edit', user.id)">
            <span class="text-white">Edit</span>
            </Link>
        </template>

        <section>
            <div class="pt-6 pb-3 text-white">
                <div class="mx-auto max-w-7xl space-y-1 sm:px-6 lg:px-8">
                    <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                        Name: {{ user.first_name }} {{ user.last_name }}
                    </div>
                </div>
            </div>
            <div class="py-3 text-white">
                <div class="mx-auto max-w-7xl space-y-1 sm:px-6 lg:px-8">
                    <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                        <span class="font-semibold">Email:</span> {{ user.email }}
                        <span class="ml-4">
                            <input type="checkbox" :checked="!!user.email_verified_at" disabled class="align-middle" />
                            <span class="ml-2 align-middle">
                                {{ user.email_verified_at ? 'Verified' : 'Not Verified' }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="py-3 text-white">
                <div class="mx-auto max-w-7xl space-y-1 sm:px-6 lg:px-8">
                    <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                        <span class="font-semibold">Created:</span> {{ formatDateTime(user.created_at) }}
                        <span class="font-semibold">Updated:</span> {{ formatDateTime(user.updated_at) }}
                    </div>
                </div>
            </div>
        </section>

    </AuthenticatedLayout>
</template>
