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
import axios from "axios";
import Textarea from "primevue/textarea";

const toast = useToast();

const form = ref({
    title: '',
    description: '',
    budget: null,
});

const submitForm = () => {
    if (!validateForm()) {
        return;
    }

    axios.post('/assignments', form.value)
        .then((response) => {
            toast.add({
                severity: 'success',
                summary: 'Success',
                detail: 'Assignment created successfully. Redirecting...'
            });
            form.value.title = '';
            form.value.description = '';
            form.value.budget = null;

            //wait 3 sec and redirect
            setTimeout(() => {
                window.location.href = '/assignments/' + response.data.assignment;
            }, 3000);
        })
        .catch(() => {
            toast.add({severity: 'error', summary: 'Error', detail: 'Failed to create assignment'});
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
    <AuthenticatedLayout title="Create assignment">
        <h1>Create Assignment</h1>
        <p>Please fill in the details below and I will reach out to you very soon!</p>
        <Divider/>
        <br>
        <div class="form flex flex-col">
            <label for="title">Title *</label>
            <InputText id="title" minlength="1" maxlength="255" v-model="form.title" required type="text"/>
        </div>

        <div class="form flex flex-col">
            <label for="description">Description *</label>
            <Textarea minlength="1" maxlength="5000"
                      id="description" v-model:model-value="form.description" placeholder="Explain your project..."
                      style="height: 320px"/>
        </div>

        <div class="form flex flex-col">
            <label for="budget">Budget *</label>
            <InputGroup style="height: 40px; width: 50%">
                <InputGroupAddon>$</InputGroupAddon>
                <InputNumber id="budget" min="0" max="100000" v-model="form.budget" aria-required="true"
                             placeholder="Price"/>
                <InputGroupAddon>.00</InputGroupAddon>
            </InputGroup>
        </div>
        <div>
            <Button @click="submitForm">Create</Button>
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
