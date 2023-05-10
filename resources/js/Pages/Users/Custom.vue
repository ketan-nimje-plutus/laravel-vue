<script setup>
import '../../../css/app.scss';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>
 
<script>
export default {
    created() {
        this.getUsers();
    },
    data() {
        let sortOrders = {};
        let columns = [
            { label: 'Name', name: 'name' },
            { label: 'Email', name: 'email' },
            { label: 'Date Added', name: 'created_at' },
        ];
        columns.forEach((column) => {
            sortOrders[column.name] = -1;
        });
        return {
            users: [],
            columns: columns,
            sortKey: 'created_at',
            sortOrders: sortOrders,
            length: 10,
            search: '',
            tableShow: {
                showdata: true,
            },
            pagination: {
                currentPage: 1,
                total: '',
                nextPage: '',
                prevPage: '',
                nextPageUrl: '',
                prevPageUrl: '',
                from: '',
                to: ''
            },
        }
    },
    methods: {
        deleteUser(id) {
            axios.delete(`/admin/custome/${id}/delete`).then(() => {
                swal(
                    'Success!',
                    'User deleted',
                    'success'
                )
            }).catch(() => {
                swal('Failed', 'There was something wrong', 'warning');
            });
        },
        getUsers() {
            axios.get('/admin/custome/', { params: { showdata: this.tableShow.showdata, sortOrders: this.sortOrders, length: this.length, search: this.search, page: this.pagination.currentPage } })
                .then(response => {
                    this.pagination.total = response.data.data.total;
                    this.pagination.from = response.data.data.from;
                    this.pagination.to = response.data.data.to;
                    this.pagination.nextPage = response.data.data.next_page_url ? response.data.data.current_page + 1 : '';
                    this.pagination.prevPage = response.data.data.current_page > 1 ? response.data.data.current_page - 1 : '';
                    this.pagination.currentPage = response.data.data.current_page;
                    this.pagination.nextPageUrl = response.data.data.next_page_url;
                    this.pagination.prevPageUrl = response.data.data.prev_page_url;
                    this.users = response.data.data.data;
                })
                .catch(errors => {
                    console.log(errors);
                });
        },
        resetPagination() {
            this.pagination.currentPage = 1;
            this.getUsers()
        },
        sortBy(key) {
            this.resetPagination();
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        },
        getIndex(array, key, value) {
            return array.findIndex(i => i[key] == value)
        },
    },
    computed: {
        paginatedUsers() {
            this.getUsers()
        }
    }
};
</script>
<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="users-style">
                            <div style="margin-bottom: 20px;">
                                <h2>Custome Datatable</h2>
                            </div>
                            <div class="table-style">
                                <div class="control">
                                    <div class="select">
                                        <select v-model="length" @change="resetPagination()">
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                        </select>
                                    </div>
                                </div>
                                <input class="input" type="text" v-model="search" placeholder="Search..."
                                    @input="resetPagination()" style="width: 250px;">
                            </div>
                            <table class="table-fixed w-full">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th v-for="column in columns" :key="column.name" @click="sortBy(column.name)"
                                            :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'"
                                            class="px-4 py-2" style="width: 40%; cursor:pointer;">
                                            {{ column.label }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in users" :key="user.id">
                                        <td class="border px-4 py-2">{{ user.name }}</td>
                                        <td class="border px-4 py-2">{{ user.email }}</td>
                                        <td class="border px-4 py-2">{{ user.created_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div>
                                <nav class="pagination" v-if="!tableShow.showdata">
                                    <span class="page-stats">{{ pagination.from }} - {{ pagination.to }} of {{
                                        pagination.total
                                    }}</span>
                                    <a v-if="pagination.prevPageUrl" class="btn btn-sm btn-primary pagination-previous"
                                        @click="--pagination.currentPage"> Prev </a>
                                    <a class="btn btn-sm btn-primary pagination-previous" v-else disabled> Prev </a>
                                    <a v-if="pagination.nextPageUrl" class="btn btn-sm pagination-next"
                                        @click="++pagination.currentPage">
                                        Next
                                    </a>
                                    <a class="btn btn-sm btn-primary pagination-next" v-else disabled> Next </a>
                                </nav>
                                <nav class="pagination" v-else>
                                    <span class="page-stats">
                                        {{ pagination.from }} - {{ pagination.to }} of {{ pagination.total }}
                                    </span>
                                    <a v-if="pagination.prevPage" class="btn btn-sm btn-primary pagination-previous"
                                        @click="--pagination.currentPage && getUsers()"> Prev </a>
                                    <a class="btn btn-sm pagination-previous btn-primary" v-else disabled> Prev </a>
                                    <a v-if="pagination.nextPage" class="btn btn-sm btn-primary pagination-next"
                                        @click="++pagination.currentPage && getUsers()"> Next </a>
                                    <a class="btn btn-sm pagination-next btn-primary" v-else disabled> Next </a>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>