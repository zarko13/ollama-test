<template>

    <Head title="Documents" />

    <AuthenticatedLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">


                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                    <form>
                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                <input type="text" id="name" name="name" v-model="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Name" required />
                            </div>
                            <div>

                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
                                <input  @change="onFileChanged($event)" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" type="file">

                            </div>

                        </div>
                        <button :disabled="!name || !file || submitting" @click="storeDocument" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                    </form>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';

export default {
    data: () => ({
        name: [],
        file: null,
        submitting : false
    }),

    components: { AuthenticatedLayout, Head },
    mounted: async function () {
    },
    methods: {
        storeDocument: async function () {
            this.submitting = true;
            const formData = new FormData();
            formData.append('file', this.file);
            formData.append('name', this.name);
            await axios.post('/async/store-document', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then((response) => {
                this.$toast.success('New document created.');
                window.location = '/documents'
            }).catch((error) => {
                this.submitting = false;
                for (let index = 0; index < error.response.data.length; index++) {
                    this.$toast.error(error.response.data[index]);
                }
            });
        },
        onFileChanged:function ($event) {
            const target = $event.target;
            if (target && target.files) {
                this.file = target.files[0];
            }

        }
    },


}
</script>
