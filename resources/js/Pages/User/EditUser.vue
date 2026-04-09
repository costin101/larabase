<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

// Access the props correctly
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

// Computed property to format the page title
const pageTitle = computed(() => `Edit ${props.user.name}`);
  

</script>

<template>
    <!-- Dynamically set the page title with the user.name -->
    <Head :title="pageTitle" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Edit User {{ user.name }}
            </h2>
            <Link :href="route('user.show', user.id)">
            <span class="text-white">Show</span>
            </Link>
        </template>

        <section>
            <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <UpdateProfileInformationForm
                        :user="user"
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <UpdatePasswordForm 
                    :user="user"
                    class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <DeleteUserForm 
                    :user="user"
                    class="max-w-xl" />
                </div>
            </div>
        </div>
        </section>
    </AuthenticatedLayout>
</template>
