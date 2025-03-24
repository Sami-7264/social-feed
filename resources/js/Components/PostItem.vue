<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import CommentForm from '@/Components/CommentForm.vue';

const props = defineProps({
    post: {
        type: Object,
        required: true
    }
});

const localComments = ref(props.post.comments || []);
const showComments = ref(false);

function toggleComments() {
    showComments.value = !showComments.value;
}

function addComment(comment) {
    localComments.value.push(comment);
}

let echo = null;

onMounted(() => {
    echo = window.Echo.channel(`post.${props.post.id}`)
        .listen('CommentCreated', (e) => {
            const newComment = e;

            const exists = localComments.value.some(comment => comment.id === newComment.id);

            if (!exists) {
                localComments.value.push(newComment);
            }
        });
});

onUnmounted(() => {
    if (echo) {
        echo.stopListening('CommentCreated');
    }
});
</script>

<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-4">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                    {{ post.user.name.charAt(0) }}
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900">
                    {{ post.user.name }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ post.created_at }}
                </p>
                <div class="mt-2 text-sm text-gray-700">
                    {{ post.content }}
                </div>

                <div class="mt-4 flex items-center space-x-4">
                    <button
                        @click="toggleComments"
                        class="text-sm text-gray-500 hover:text-gray-700"
                    >
                        {{ showComments ? 'Hide Comments' : `Show Comments (${localComments.length})` }}
                    </button>
                    <Link
                        :href="`/posts/${post.id}`"
                        class="text-sm text-indigo-600 hover:text-indigo-800"
                    >
                        View Post
                    </Link>
                </div>

                <div v-if="showComments" class="mt-4">
                    <div class="space-y-4 mb-4">
                        <div v-if="localComments.length === 0" class="text-gray-500 text-sm">
                            No comments yet. Be the first to comment!
                        </div>

                        <div
                            v-for="comment in localComments"
                            :key="comment.id"
                            class="flex items-start space-x-3 border-l-2 border-gray-200 pl-3"
                        >
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-white font-bold">
                                    {{ comment.user.name.charAt(0) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ comment.user.name }}
                                    </p>
                                    <span class="mx-1 text-gray-300">•</span>
                                    <p class="text-xs text-gray-500">
                                        {{ comment.created_at }}
                                    </p>
                                </div>
                                <div class="mt-1 text-sm text-gray-700">
                                    {{ comment.content }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <CommentForm
                        :post-id="post.id"
                        @comment-created="addComment"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
