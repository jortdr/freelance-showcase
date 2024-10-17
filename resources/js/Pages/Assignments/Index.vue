<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Divider from "primevue/divider";
import Button from "primevue/button";
import {Link} from '@inertiajs/vue3';
import NavLink from "@/Components/NavLink.vue";
import axios from "axios";
import {useToast} from "primevue/usetoast";
import Toast from "primevue/toast";
import {ref} from "vue";

const toast = useToast();

const props = defineProps({
        assignments: {
            type: Array,
            required: true,
        },
        isAdmin: {
            type: Boolean,
            required: true,
        }
    }
)
const strippedDesc = (description) => {
    return description.replace(/<\/?[^>]+>/ig, " ");
}

const assignments = ref(props.assignments);
console.log(props.isAdmin);

const deleteAssignment = (id) => {
    if (confirm('Are you sure you want to delete this assignment?')) {
        axios.delete(`/assignments/${id}`)
            .then(() => {
                toast.add({severity: 'success', summary: 'Success', detail: 'Assignment deleted successfully'});
                //remove the assignment from the list
                assignments.value = assignments.value.filter(assignment => assignment.id !== id);
            })
            .catch(() => {
                toast.add({severity: 'error', summary: 'Error', detail: 'Failed to delete assignment'});
            });
    }
}
</script>

<template>
    <Toast/>
    <AuthenticatedLayout title="Assignments">
        <div class="flex flex-row justify-between">
            <div>
                <h1>Assignments</h1>
                <p>Here you can see your assignments.</p>
            </div>
            <NavLink :href="route('assignments.create')">
                <Button label="Create Assignment" outlined icon="pi pi-plus"/>
            </NavLink>
        </div>
        <Divider/>
        <DataTable :value="assignments">
            <Column v-if="isAdmin" field="user.name" header="User"></Column>
            <Column field="title" header="Title"></Column>
            <Column field="description" header="Description">
                <template #body="slotProps">

                    {{ strippedDesc(slotProps.data.description).substring(0, 50) }}
                    <span v-if="strippedDesc(slotProps.data.description).length > 50">...</span>
                </template>
            </Column>
            <Column field="budget" header="Budget">
                <template #body="slotProps">
                    ${{ slotProps.data.budget }} USD
                </template>
            </Column>
            <Column header="Actions">
                <template #body="slotProps">
                    <Link :href="route('assignments.show', slotProps.data.id)" class="mr-5">
                        <Button icon="pi pi-eye" size="small" outlined label="Open"/>
                    </Link>
                    <Link v-if="!isAdmin" :href="route('assignments.show', slotProps.data.id)" class="mr-5">
                        <Button icon="pi pi-pencil" size="small" label="Edit"/>
                    </Link>

                    <Button
v-if="!isAdmin"
                            icon="pi pi-trash" class="p-button-danger" size="small"
                            label="Delete" @click="deleteAssignment(slotProps.data.id)"/>

                </template>
            </Column>
        </DataTable>
    </AuthenticatedLayout>
</template>
