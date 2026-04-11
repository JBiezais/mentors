import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import i18n from './plugins/i18n';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

function applyAccentPalette(palette) {
    if (!palette || typeof palette !== 'object') return;
    const shades = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900'];
    shades.forEach((shade) => {
        if (palette[shade]) {
            document.documentElement.style.setProperty(`--accent-${shade}`, palette[shade]);
        }
    });
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        applyAccentPalette(props.initialPage?.props?.accentScheme?.palette);

        router.on('success', (event) => {
            const page = event?.detail?.page;
            if (page?.props?.accentScheme?.palette) {
                applyAccentPalette(page.props.accentScheme.palette);
            }
        });

        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
