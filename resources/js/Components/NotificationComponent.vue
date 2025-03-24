<script setup>
import { ref, onMounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const notifications = ref([]);
const showNotifications = ref(false);
const currentUser = computed(() => usePage().props.auth.user);

const toggleNotifications = () => {
    showNotifications.value = !showNotifications.value;
};

const addNotification = (message, type = 'info', userId) => {
    if (userId === currentUser.value.id) {
        return;
    }

    const id = Date.now();
    notifications.value.unshift({id, message, type, read: false});

    if (showNotifications.value) {
        setTimeout(() => {
            markAsRead(id);
        }, 5000);
    }
};

const markAsRead = (id) => {
    const notification = notifications.value.find(n => n.id === id);
    if (notification) {
        notification.read = true;
    }
};

const markAllAsRead = () => {
    notifications.value.forEach(notification => {
        notification.read = true;
    });
};

const unreadCount = computed(() => {
    return notifications.value.filter(n => !n.read).length;
});

onMounted(() => {
    window.Echo.channel('posts')
        .listen('PostCreated', (e) => {
            console.log('PostCreated event received:', e);
            const post = e.post || e; // Handle different event structures
            if (post && post.user) {
                addNotification(`${post.user.name} created a new post`, 'post', post.user.id);
            }
        });

    window.Echo.channel('notifications')
        .listen('CommentCreated', (e) => {
            console.log('CommentCreated event received:', e);
            const comment = e.comment || e; // Handle different event structures
            if (comment && comment.user) {
                addNotification(`${comment.user.name} commented on a post`, 'comment', comment.user.id);
            }
        });
});

defineExpose({
    addNotification
});
</script>

<template>
    <div class="relative">
        <button @click="toggleNotifications" class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>

            <span v-if="unreadCount > 0"
                  class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                {{ unreadCount }}
            </span>
        </button>

        <div v-if="showNotifications"
             class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-50">
            <div class="py-2 px-3 bg-gray-100 flex justify-between items-center">
                <span class="text-sm font-medium">Notifications</span>
                <button @click="markAllAsRead" class="text-xs text-blue-500 hover:text-blue-700">
                    Mark all as read
                </button>
            </div>

            <div class="max-h-96 overflow-y-auto">
                <div v-if="notifications.length === 0" class="py-4 px-3 text-sm text-gray-500 text-center">
                    No notifications yet
                </div>

                <div v-for="notification in notifications" :key="notification.id"
                     class="py-2 px-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100"
                     :class="{ 'bg-blue-50': !notification.read }"
                     @click="markAsRead(notification.id)">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-2">
                            <svg v-if="notification.type === 'post'" xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"
                                      clip-rule="evenodd"/>
                            </svg>

                            <svg v-else-if="notification.type === 'comment'" xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                <path
                                    d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                            </svg>

                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-800">{{ notification.message }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{
                                    new Date(notification.id).toLocaleTimeString()
                                }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
