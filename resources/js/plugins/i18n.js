import { createI18n } from 'vue-i18n';
import lv from '../locales/lv.json';
import en from '../locales/en.json';

const locale = window.Laravel?.locale ?? 'lv';

export default createI18n({
    legacy: false,
    globalInjection: true,
    locale,
    fallbackLocale: 'lv',
    messages: { lv, en },
});
