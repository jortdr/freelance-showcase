<template>
    <div>
        <div class="flex flex-col justify-end h-[40rem]">
            <div ref="messagesContainer" class="p-4 overflow-y-auto overflow-x-clip max-h-fit">
                <div
                    v-for="message in messages"
                    :key="message.id"
                    class="flex items-center mb-2"
                >
                    <div v-if="message.sender_id === currentUser.id" class="ml-auto text-right max-w-full">
                        <p class="p-2 ml-auto text-white bg-blue-500 rounded-lg text-ellipsis ">
                            {{ message.text }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ currentUser.name }} - {{ formatTimestamp(message.created_at) }}
                        </p>
                    </div>
                    <div v-else class="mr-auto text-left max-w-full">
                        <p class="p-2 mr-auto bg-gray-200 rounded-lg text-ellipsis ">
                            {{ message.text }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ friend.name }} - {{ formatTimestamp(message.created_at) }}
                        </p>
                    </div>
                </div>
                <div v-if="messages.length === 0" class="text-center">
                    <p class="text-gray-500">No messages yet. Start the conversation!</p>
                </div>
            </div>
        </div>
        <div class="flex items-center">
            <input
                v-model="newMessage"
                type="text"
                placeholder="Type a message..."
                class="flex-1 px-2 py-1 border rounded-lg"
                @keydown="sendTypingEvent"
                @keyup.enter="sendMessage"
            />
            <button
                class="px-4 py-1 ml-2 text-white bg-blue-500 rounded-lg"
                @click="sendMessage"
            >
                Send
            </button>
        </div>
        <small v-if="isFriendTyping" class="text-gray-700">
            {{ friend.name }} is typing...
        </small>
    </div>
</template>

<script setup>
import axios from "axios";
import {nextTick, onMounted, ref, watch} from "vue";

const props = defineProps({
    friend: {
        type: Object,
        required: true,
    },
    currentUser: {
        type: Object,
        required: true,
    },
    toast: {
        type: Object,
        required: true,
    },
});

const messages = ref([]);
const newMessage = ref("");
const messagesContainer = ref(null);
const isFriendTyping = ref(false);
const isFriendTypingTimer = ref(null);

watch(
    messages,
    () => {
        nextTick(() => {
            messagesContainer.value.scrollTo({
                top: messagesContainer.value.scrollHeight,
                behavior: "smooth",
            });
        });
    },
    {deep: true}
);

const formatTimestamp = (timestamp) => {
    return new Date(timestamp).toLocaleTimeString("en-GB", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const sendMessage = () => {
    if (newMessage.value.trim() !== "") {
        axios
            .post(`/messages/${props.friend.id}`, {
                message: newMessage.value,
            })
            .catch((error) => processError(error))
            .then((response) => {
                messages.value.push(response.data);
                newMessage.value = "";
            });
    }
};

const processError = (error) => {
    if (error.response.status === 429) {
        props.toast.add({
            severity: "error",
            summary: "Error",
            detail: "You are sending too many messages. Please wait a few seconds.",
            life: 5000,
        });
    } else {
        props.toast.add({
            severity: "error",
            summary: "Error",
            detail: "Failed to send message",
            life: 5000,
        });
    }
};

const sendTypingEvent = () => {
    Echo.private(`chat.${props.currentUser.id}.${props.friend.id}`).whisper("typing", {
        userID: props.currentUser.id,
    });
};

onMounted(() => {
    axios.get(`/messages/${props.friend.id}`)
        .catch((error) => processError(error))
        .then((response) => {
            console.log(response.data);
            messages.value = response.data;
        });

    Echo.private(`chat.${props.currentUser.id}.${props.friend.id}`)
        .listen("MessageSent", (response) => {
            messages.value.push(response.message);
        })
        .listenForWhisper("typing", (response) => {
            isFriendTyping.value = response.userID === props.friend.id;

            if (isFriendTypingTimer.value) {
                clearTimeout(isFriendTypingTimer.value);
            }

            isFriendTypingTimer.value = setTimeout(() => {
                isFriendTyping.value = false;
            }, 1000);
        });
});
</script>
