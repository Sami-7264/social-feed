<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PostForm from '@/Components/PostForm.vue';
import PostList from '@/Components/PostList.vue';
import { ref } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    posts: Array,
});

const localPosts = ref(props.posts || []);

function addPost(post) {
    localPosts.value.unshift(post);
}
</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Social Feed
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <PostForm @post-created="addPost" />
                    </div>
                </div>

                <div class="mt-6">
                    <PostList :posts="localPosts" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
