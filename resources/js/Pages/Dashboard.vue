<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    data: Array,
    isOpen: Boolean,
    editMode: Boolean,
});

const isOpen = ref(null);
const editMode = ref(null);

const form = useForm({
    title: '',
    body: '',
    id: '',
});

const edit = (data) => {
    form.title = data.title;
    form.body = data.body;
    form.id = data.id;
    isOpen.value = true;
    editMode.value = true;
}

const deleteRow = (data) => {
    if (!confirm('Are you sure want to remove?')) return;
    form.title = data.title;
    form.body = data.body;
    form.id = data.id;
    form._method = 'DELETE';
    form.delete('/admin/posts/' + form.id, {
        preserveScroll: true,
        onError: () => {

        },
    });

    isOpen.value = false;
}

const save = () => {
    form.put('/admin/posts/' + form.id, {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {

        },
    });
    isOpen.value = false;
};

const closeModal = () => {
    isOpen.value = false;
}
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <table class="table-fixed w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 w-20">No.</th>
                                <th class="px-4 py-2">Title</th>
                                <th class="px-4 py-2">Body</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in data">
                                <td class="border px-4 py-2">{{ row.id }}</td>
                                <td class="border px-4 py-2">{{ row.title }}</td>
                                <td class="border px-4 py-2">{{ row.body }}</td>
                                <td class="border px-4 py-2">
                                    <button @click="edit(row)"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                        style="margin: 0.25rem;">Edit</button>
                                    <button @click="deleteRow(row)"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded "
                                        style="margin: 0.25rem;">Delete</button>
                                </td>
                            </tr>
                            <tr v-if="data.length == 0">
                                <td colspan="4" class="border px-4 py-2" style="text-align: center;">No Data Found</td>
                            </tr>
                        </tbody>
                    </table>
                    <Teleport to="body">
                        <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
                            <div
                                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                <div class="fixed inset-0 transition-opacity">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>
                                <!-- This element is to trick the browser into centering the modal contents. -->
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                    role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                    <form @submit.prevent="save">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="">
                                                <div class="mb-4">
                                                    <label for="exampleFormControlInput1"
                                                        class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                                                    <input type="text"
                                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                        id="exampleFormControlInput1" placeholder="Enter Title"
                                                        v-model="form.title">
                                                    <!-- <div v-if="page.errors.title" class="text-red-500">{{ page.errors.title[0] }}</div> -->
                                                </div>
                                                <div class="mb-4">
                                                    <label for="exampleFormControlInput2"
                                                        class="block text-gray-700 text-sm font-bold mb-2">Body:</label>
                                                    <textarea
                                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                        id="exampleFormControlInput2" v-model="form.body"
                                                        placeholder="Enter Body"></textarea>
                                                    <div class="text-red-500"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">

                                            <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto"
                                                v-show="editMode">
                                                <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
                                            </span>
                                            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                                <button @click="closeModal()" type="button"
                                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                    Cancel
                                                </button>
                                            </span>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </Teleport>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
