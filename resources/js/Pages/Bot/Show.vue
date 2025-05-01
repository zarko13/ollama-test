<template>

    <Head title="Bots" />

    <AuthenticatedLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

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
    },


}
</script>
