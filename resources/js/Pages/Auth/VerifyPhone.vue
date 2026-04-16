<script setup>
import { ref, nextTick } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: { type: String },
});

// We keep the form as a single string for the backend
const form = useForm({
    code: '',
});

// We use an array to manage the 6 individual boxes in the UI
const digits = ref(['', '', '', '', '', '']);
const inputRefs = ref([]);

const updateCode = () => {
    form.code = digits.value.join('');
    // Auto-submit if all 6 boxes are filled
    if (form.code.length === 6) {
        submit();
    }
};

const handleInput = (index, event) => {
    const value = event.target.value;
    // Only take the last character typed (prevents double digits)
    digits.value[index] = value.slice(-1);
    
    updateCode();

    // Move focus forward
    if (value && index < 5) {
        inputRefs.value[index + 1].focus();
    }
};

const handleKeyDown = (index, event) => {
    // Move focus back on backspace if current box is empty
    if (event.key === 'Backspace' && !digits.value[index] && index > 0) {
        inputRefs.value[index - 1].focus();
    }
};

const handlePaste = (event) => {
    event.preventDefault();
    const pasteData = event.clipboardData.getData('text').slice(0, 6).split('');
    pasteData.forEach((char, i) => {
        if (i < 6) digits.value[i] = char;
    });
    updateCode();
};

const submit = () => {
    form.post(route('verification.verify-code'), {
        onFinish: () => form.reset('code'),
    });
};

const resendSms = () => {
    form.post(route('verification.send'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Verify Code" />

        <div class="mb-4 text-sm text-center text-gray-600 dark:text-gray-400">
            Please enter the 6-digit code sent to your phone.
        </div>

        <form @submit.prevent="submit">
            <div class="flex justify-center gap-2 mb-6" @paste="handlePaste">
                <input
                    v-for="(digit, index) in digits"
                    :key="index"
                    ref="inputRefs"
                    type="text"
                    inputmode="numeric"
                    maxlength="1"
                    class="w-12 h-14 text-center text-2xl font-bold border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    v-model="digits[index]"
                    @input="handleInput(index, $event)"
                    @keydown="handleKeyDown(index, $event)"
                />
            </div>

            <InputError class="mt-2 text-center" :message="form.errors.code" />

            <div class="mt-4 flex items-center justify-between">
                <button
                    type="button"
                    @click="resendSms"
                    class="text-sm text-gray-600 underline hover:text-gray-900 dark:text-gray-400"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Resend Code
                </button>

                <div class="flex items-center">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="mr-4 text-sm text-gray-600 underline hover:text-gray-900 dark:text-gray-400"
                    >Log Out</Link>

                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Verify
                    </PrimaryButton>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>