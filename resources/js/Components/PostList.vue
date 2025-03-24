<script setup>
import { onMounted, onUnmounted } from 'vue';
import PostItem from '@/Components/PostItem.vue';

const props = defineProps({
    posts: {
        type: Array,
        default: () => []
    }
});

let echo = null;

onMounted(() => {
    echo = window.Echo.channel('posts')
        .listen('PostCreated', (e) => {
            console.log('Received PostCreated event:', e);
            const newPost = e;
            console.log(newPost, "newPost");

            if (newPost && !newPost.comments) {
                newPost.comments = [];
            }

            if (newPost && props.posts) {
                const exists = props.posts.some(post => post.id === newPost.id);
                if (!exists) {
                    props.posts.unshift(newPost);
                }
            }
        });
});

onUnmounted(() => {
    if (echo) {
        echo.stopListening('PostCreated');
    }
});
</script>

<template>
    <div>
        <div v-if="!posts || posts.length === 0" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center text-gray-500">
            No posts yet. Be the first to post!
        </div>

        <div v-else class="space-y-4">
            <PostItem
                v-for="post in posts"
                :key="post.id"
                :post="post"
            />
        </div>
    </div>
</template>
