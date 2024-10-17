<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Button from "primevue/button";
import ChatComponent from "@/Components/ChatComponent.vue";
import NavLink from "@/Components/NavLink.vue";
import Textarea from "primevue/textarea";
import {useToast} from "primevue/usetoast";
import Toast from "primevue/toast";

const toast = useToast();

defineProps({
    assignment: {
        type: Object,
        required: true,
    },
    currentUser: {
        type: Object,
        required: true,
    },
    receiver: {
        type: Object,
        required: true,
    },
});

</script>

<template>
    <Toast/>
    <AuthenticatedLayout title="Update assignment">
        <h1>Assignment: {{ assignment.title }}</h1>
        <div class="flex w-full flex-row h-[48rem]">
            <div class="w-1/3 border-r-2 mr-4">
                <h1>Assignment Information</h1>
                <p><strong>Title:</strong> {{ assignment.title }}</p>
                <p><strong>Description</strong></p>
                <Textarea
                    :value="assignment.description" readonly style="resize:none;"
                    class="w-full h-[50vh] border-0 m-0 p-5"/>
                <p><strong>Budget:</strong> ${{ assignment.budget }} USD</p>
                <NavLink :href="route('assignments.edit', assignment.id)">
                    <Button label="Edit Assignment" class="mt-4" icon="pi pi-pencil"/>
                </NavLink>

            </div>
            <div class="w-2/3 h-full">
                <h1>Chat Window</h1>
                <ChatComponent :current-user="currentUser" :friend="receiver" :toast="toast" class="h-full"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
