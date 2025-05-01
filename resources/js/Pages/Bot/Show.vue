<template>

    <Head title="Bots" />

    <AuthenticatedLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="inline-flex rounded-md shadow-xs justify-end" role="group">
                <a :href="'/bot/' + bot.id + '/add-instruction'"><button type="button"
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Add
                        Instruction</button></a>
                <a :href="'/bot/' + bot.id + '/add-document'"><button type="button"
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Add
                        Documents</button></a>
            </div>
            <h2>Information</h2>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                    <dl class="max-w-md text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
                        <div class="flex flex-col pb-3">
                            <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Name</dt>
                            <dd class="text-lg font-semibold">{{ bot.name }}</dd>
                        </div>
                        <div class="flex flex-col py-3">
                            <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Model</dt>
                            <dd class="text-lg font-semibold">{{ bot.model }}</dd>
                        </div>
                    </dl>

                </div>

            </div>
            <h2>Instructions</h2>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Content
                                </th>
                                <th scope="col" class="px-6 py-3">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(instruction , index) in instructions" :key="index"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ instruction.name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ instruction.content }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ instruction.content }}
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>

            </div>

            <h2>Documents</h2>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                </th>
                                <th scope="col" class="px-6 py-3">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(document , i) in documents" :key="i"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ document.name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ document.display_status }}
                                </td>
                                <td class="px-6 py-4">
                                    <a target="_blank" :href="document.display_path">
                                        <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Download</button>
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <button @click="deleteDocument(document)" type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Delete</button>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
    data: () => ({
    }),
    props: {
        bot: Object,
        instructions: Array,
        documents: Array
    },
    components: { AuthenticatedLayout, Head },
    mounted: async function () {
    },
    methods: {
        deleteDocument: async function (document) {
            await axios({
                method: "POST",
                data: {
                    reference : document.id
                },
                url: "/async/delete-document",
            }).then((response) => {
                window.location.reload();
            }).catch((error) => {
                for (let index = 0; index < error.response.data.length; index++) {
                    this.$toast.error(error.response.data[index]);
                }
            });
        },
    },


}
</script>
