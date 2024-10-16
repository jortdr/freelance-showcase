<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputText from "primevue/inputtext";
import {ref} from "vue";
import InputGroup from "primevue/inputgroup";
import InputGroupAddon from "primevue/inputgroupaddon";
import InputNumber from "primevue/inputnumber";
import Button from "primevue/button";
import Divider from "primevue/divider";
import Toast from "primevue/toast";
import {useToast} from "primevue/usetoast";
import Textarea from "primevue/textarea";
import axios from "axios";

const toast = useToast();

const props = defineProps({
    assignment: {
        type: Object,
        required: true,
    },
})

const form = ref({
    title: props.assignment.title,
    description: props.assignment.description,
    budget: props.assignment.budget,
});

const submitForm = () => {
    if (!validateForm()) {
        return;
    }

    axios.patch('/assignments/' + props.assignment.id, form.value)
        .then(() => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Assignment updated successfully. Redirecting...'
            });
            //wait 3 sec and redirect
            setTimeout(() => {
                window.location.href = route('assignments.show', props.assignment.id);
            }, 3000);
        })
        .catch(() => {
            toast.add({severity: 'error', summary: 'Error', detail: 'Failed to update assignment'});
        });
}
const validateForm = () => {
    if (!form.value.title) {
        showError('Title is required');
        return false;
    }
    if (!form.value.description) {
        showError('Description is required');
        return false;
    }
    if (!form.value.budget) {
        showError('Budget is required');
        return false;
    }
    return true;
}

const showError = (message) => {
    toast.add({severity: 'error', summary: 'Error', detail: message, life: 5000});
}
</script>

<template>
    <Toast/>
    <AuthenticatedLayout title="Update assignment">
        <h1>Update Assignment</h1>
        <p>Feel free to update your assignment.</p>
        <Divider/>
        <br>
        <div class="form flex flex-col">
            <label for="title">Title *</label>
            <InputText id="title" v-model="form.title" required type="text"/>
        </div>

        <div class="form flex flex-col">
            <label for="description">Description *</label>
            <Textarea
id="description" v-model:model-value="form.description"
                      style="height: 320px"/>
        </div>

        <div class="form flex flex-col">
            <label for="budget">Budget *</label>
            <InputGroup style="height: 40px; width: 50%">
                <InputGroupAddon>$</InputGroupAddon>
                <InputNumber id="budget" v-model="form.budget" aria-required="true" placeholder="Price"/>
                <InputGroupAddon>.00</InputGroupAddon>
            </InputGroup>
        </div>
        <div>
            <Button @click="submitForm">Update</Button>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.form {
    label {
        font-weight: bold;
        font-size: x-large;
    }

    input {
        width: 50%;
        height: 40px;
    }

    margin-bottom: 20px;
}
</style>
