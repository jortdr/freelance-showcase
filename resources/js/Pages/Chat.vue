<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ChatComponent from "@/Components/ChatComponent.vue";

const props = defineProps({
    friend: {
        type: Object,
        required: true,
    },
    currentUser: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <AuthenticatedLayout title="Show assignment">
        Chat time! This is your chat with {{ friend.name }}.
        <ChatComponent :current-user="currentUser" :friend="friend"/>
    </AuthenticatedLayout>
</template>
