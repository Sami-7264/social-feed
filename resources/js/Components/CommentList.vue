<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    postId: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['comment-created']);

const form = useForm({
    content: '',
});

const isSubmitting = ref(false);

function submit() {
    isSubmitting.value = true;
    form.post(`/posts/${props.postId}/comments`, {
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
    <div class="mt-4">
        <form @submit.prevent="submit">
            <textarea
                v-model="form.content"
                class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                rows="2"
                placeholder="Write a comment..."
                required
            ></textarea>

            <div v-if="form.errors.content" class="text-red-500 mt-1">
                {{ form.errors.content }}
            </div>

            <div class="mt-2 flex justify-end">
                <button
                    type="submit"
                    class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition"
                    :disabled="form.processing || isSubmitting"
                >
                    <span v-if="form.processing || isSubmitting">Posting...</span>
                    <span v-else>Comment</span>
                </button>
            </div>
        </form>
    </div>
</template>
