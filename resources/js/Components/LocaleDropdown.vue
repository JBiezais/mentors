<template>
    <Dropdown align="right" width="48" :content-classes="['py-1', 'bg-white', 'rounded-none']">
        <template #trigger>
            <button
                type="button"
                class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 border border-accent-500 text-accent-600 hover:bg-accent-50 font-medium rounded-none transition-colors duration-200"
            >
                {{ currentLocaleLabel }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </template>
        <template #content>
            <a
                :href="route('locale.switch', { locale: 'lv' })"
                class="block w-full px-4 py-2.5 text-left text-sm leading-5 transition duration-150 ease-in-out rounded-none"
                :class="isLv ? 'bg-accent-50 text-accent-700 font-medium' : 'text-gray-700 hover:bg-gray-100'"
            >
                {{ $t('home.nav.langLv') }}
            </a>
            <a
                :href="route('locale.switch', { locale: 'en' })"
                class="block w-full px-4 py-2.5 text-left text-sm leading-5 transition duration-150 ease-in-out rounded-none"
                :class="isEn ? 'bg-accent-50 text-accent-700 font-medium' : 'text-gray-700 hover:bg-gray-100'"
            >
                {{ $t('home.nav.langEn') }}
            </a>
        </template>
    </Dropdown>
</template>

<script>
import Dropdown from '@/Components/Dropdown.vue';

export default {
    name: 'LocaleDropdown',
    components: { Dropdown },
    computed: {
        locale() {
            return this.$page?.props?.locale ?? 'lv';
        },
        isLv() {
            return this.locale === 'lv';
        },
        isEn() {
            return this.locale === 'en';
        },
        currentLocaleLabel() {
            return this.locale === 'lv' ? this.$t('home.nav.langLv') : this.$t('home.nav.langEn');
        }
    }
};
</script>
