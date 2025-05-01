<template>

    <Head title="Bots" />

    <AuthenticatedLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">


            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                <div class="pb-4 bg-white dark:bg-gray-900">
                    <div
                        class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                        <div class="inline-flex rounded-md shadow-xs justify-end" role="group">
                            <a href="/bot"><button type="button"
                                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Create</button></a>
                        </div>
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative mt-1">
                            <div
                                class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </div>
                            <input name="name" v-model="name" @keyup="debounceInput" type="text" id="table-search"
                                class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Search bots">
                        </div>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Model
                                </th>
                                <th scope="col" class="px-6 py-3">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(bot, index) in bots" :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ bot.name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ bot.model }}
                                </td>
                                <td class="px-6 py-4">
                                    <a :href="'/bot/' + bot.id ">
                                        <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Details</button>
                                    </a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <vue-awesome-paginate v-if="pagination.total > pagination.per_page" :total-items="pagination.total"
                        :items-per-page="pagination.per_page" :max-pages-shown="5" v-model="pagination.page"
                        @click="changePage" />
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import _ from 'lodash';

export default {
    data: () => ({
        bots: [],
        name: null,

        pagination: {
            total: 0,
            per_page: 15,
            page: 1
        },

        filters: [],
    }),

    components: { AuthenticatedLayout, Head },
    mounted: async function () {
        this.repopulateInput();
        this.loadData();
    },
    methods: {
        getBots: async function () {
            await axios({
                method: "GET",
                params: this.$getUrlParamsAsObj(),
                url: "/async/bots",
            })
                .then((response) => {
                    this.pagination.total = response.data.bots.total;
                    this.pagination.per_page = response.data.bots.per_page;
                    this.pagination.page = response.data.bots.current_page;

                    this.bots = response.data.bots.data;
                });
        },

        repopulateInput() {
            let oldInput = this.$getUrlParamsAsObj();
            for (let key in oldInput) {
                this[key] = oldInput[key];
            }
        },

        loadData() {
            this.getBots();
        },

        addFilter(key, value) {
            this.$addUrlParams(key, value);
            this.getBots();
        },

        changePage() {
            this.addFilter("page", this.pagination.page);
        },


        setFirstPage() {
            this.pagination.page = 1;
            this.$addUrlParams("page", this.pagination.page);
        },

        changeInput(event) {
            this.setFirstPage();
            this.addFilter(event.target.name, event.target.value);
        },

        debounceInput: _.debounce(function (e) {
            this.setFirstPage();
            this.addFilter(e.target.name, e.target.value);
        }, 500),
    },


}
</script>
