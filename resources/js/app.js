import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import VueUrlParams from "./lib/vue-url-params";
import VueAwesomePaginate from "vue-awesome-paginate";
import Toaster from "@meforma/vue-toaster";
import "vue-awesome-paginate/dist/style.css";
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const reverbKey = import.meta.env.VITE_REVERB_APP_KEY || 'xzg2piyb38ghrba9w0lf';
const reverbHost = import.meta.env.VITE_REVERB_HOST || 'localhost';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: reverbKey,
    wsHost: reverbHost,
    wsPort: 9000,
    wssPort: 9000,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VueUrlParams)
            .use(Toaster)
            .use(VueAwesomePaginate)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
