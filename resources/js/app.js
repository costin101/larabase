import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const rtlLanguages = ['ar', 'he', 'fa', 'ur', 'ps', 'ckb'];

createInertiaApp({
    title: (title) => `${title} - ${appName}`,

    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue') // ✅ correct
        ),

    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin).use(ZiggyVue);

        // ✅ RTL handling
        app.mixin({
            mounted() {
                const locale = this.$page.props.locale || 'en';
                document.documentElement.dir = rtlLanguages.includes(locale)
                    ? 'rtl'
                    : 'ltr';
            },
        });

        return app.mount(el);
    },

    progress: {
        color: '#4B5563',
    },
});