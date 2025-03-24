<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const emit = defineEmits(['post-created']);

const form = useForm({
    content: '',
});

const isSubmitting = ref(false);

function submit() {
    isSubmitting.value = true;
    form.post(route('posts.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            isSubmitting.value = false;
        },
        onError: () => {
            isSubmitting.value = false;
        }
    });
}
</script>

<template>
    <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4">Create Post</h3>
        <form @submit.prevent="submit">
            <textarea
                v-model="form.content"
                class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                rows="3"
                placeholder="What's on your mind?"
                required
            ></textarea>

            <div v-if="form.errors.content" class="text-red-500 mt-1">
                {{ form.errors.content }}
            </div>

            <div class="mt-4 flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition"
                    :disabled="form.processing || isSubmitting"
                >
                    <span v-if="form.processing || isSubmitting">Posting...</span>
                    <span v-else>Post</span>
                </button>
            </div>
        </form>
    </div>
</template>
