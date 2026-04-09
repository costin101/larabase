<script setup>
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

defineProps({
    users: {
        type: Object,
    }
});
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Users
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <table class="table-auto w-full border-collapse border border-gray-200 mt-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        <tr v-for="user in users.data" :key="user.id">
                            <td class="border px-4 py-2">{{ user.id }}</td>
                            <td class="border px-4 py-2">{{ user.name }}</td>
                            <td class="border px-4 py-2">{{ user.email }}</td>
                            <td class="border px-4 py-2">
                                <Link 
                                :href="route('user.show', user.id)"
                                >
                                    <span class="px-2">show</span>
                                </Link>
                                <Link 
                                :href="route('user.edit', user.id)">
                                    <span class="px-2">edit</span>
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <Pagination :links="users.links"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
