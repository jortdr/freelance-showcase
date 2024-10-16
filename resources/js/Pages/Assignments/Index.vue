<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Divider from "primevue/divider";
import Button from "primevue/button";
import {Link} from '@inertiajs/vue3';

const props = defineProps({
        assignments: {
            type: Array,
            required: true,
        },
    }
)
</script>

<template>
    <AuthenticatedLayout title="Assignments">
        <h1>Assignments</h1>
        <p>Here you can see your assignments.</p>
        <Divider/>
        <DataTable :value="assignments">
            <Column field="title" header="Title"></Column>
            <Column field="description" header="Description"></Column>
            <Column field="budget" header="Budget">
                <template #body="slotProps">
                    ${{ slotProps.data.budget }} USD
                </template>
            </Column>
            <Column header="Actions">
                <template #body="slotProps">
                    <Link :href="route('assignments.show', slotProps.data.id)" class="mr-5">
                        <Button icon="pi pi-eye" outlined label="Open"/>
                    </Link>
                    <Link :href="route('assignments.show', slotProps.data.id)" class="mr-5">
                        <Button icon="pi pi-pencil" label="Edit"/>
                    </Link>

                    <Button
                        icon="pi pi-trash" class="p-button-danger"
                        label="Delete" @click="console.log('Delete ' + slotProps.data.id)"/>

                </template>
            </Column>
        </DataTable>
    </AuthenticatedLayout>
</template>
