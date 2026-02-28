<template>
    <footer class="w-full bg-gray-900 text-gray-300 border-t border-gray-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 md:px-8 py-12 md:py-16">
            <div class="flex flex-col md:flex-row gap-8 md:gap-12 md:items-start md:justify-between">
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-400">{{ $t('footer.coordinatedBy') }}</h4>
                    <a href="https://sp.rsu.lv/" target="_blank" class="block">
                        <img class="w-full max-w-[200px] h-auto" src="/img/white-footer-logo.png" alt="RSUsp">
                    </a>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <a href="tel:+37127750555" class="hover:text-accent-400 transition-colors">+371 27 750 555</a>
                        <a href="mailto:sp@rsu.lv" class="hover:text-accent-400 transition-colors">sp@rsu.lv</a>
                    </div>
                </div>
                <div class="flex flex-col md:items-end gap-6">
                    <nav class="flex flex-col sm:flex-row gap-4 sm:gap-6 font-medium text-gray-200">
                        <a
                            v-if="scrollToSection"
                            href="#uzzini"
                            @click.prevent="scrollToSection('uzzini')"
                            class="hover:text-accent-400 transition-colors"
                        >{{ $t('home.nav.uzziniVirak') }}</a>
                        <Link
                            v-else
                            :href="route('home') + '#uzzini'"
                            class="hover:text-accent-400 transition-colors"
                        >{{ $t('home.nav.uzziniVirak') }}</Link>
                        <a
                            v-if="scrollToSection"
                            href="#atsauksmes"
                            @click.prevent="scrollToSection('atsauksmes')"
                            class="hover:text-accent-400 transition-colors"
                        >{{ $t('home.nav.atsauksmes') }}</a>
                        <Link
                            v-else
                            :href="route('home') + '#atsauksmes'"
                            class="hover:text-accent-400 transition-colors"
                        >{{ $t('home.nav.atsauksmes') }}</Link>
                        <a
                            v-if="scrollToSection"
                            href="#pieteikties"
                            @click.prevent="scrollToSection('pieteikties')"
                            class="hover:text-accent-400 transition-colors"
                        >{{ $t('home.nav.pieteikties') }}</a>
                        <Link
                            v-else
                            :href="route('home') + '#pieteikties'"
                            class="hover:text-accent-400 transition-colors"
                        >{{ $t('home.nav.pieteikties') }}</Link>
                    </nav>
                    <div class="flex gap-3">
                        <a
                            href="https://www.instagram.com/rsusp/"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center transition-colors hover:bg-accent-600"
                        >
                            <img src="/img/instagram.svg" alt="Instagram" class="w-5 h-5 brightness-0 invert opacity-70">
                        </a>
                        <a
                            href="https://www.facebook.com/RSUSP"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center transition-colors hover:bg-accent-600"
                        >
                            <img src="/img/facebook.svg" alt="Facebook" class="w-5 h-5 brightness-0 invert opacity-70">
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 md:pt-8 mt-8 md:mt-12 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm text-gray-400">
                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    <a v-if="contacts?.phone" :href="'tel:' + contacts.phone" class="hover:text-accent-400 transition-colors">{{ contacts.phone }}</a>
                    <span v-if="contacts?.phone && contacts?.email">·</span>
                    <a v-if="contacts?.email" :href="'mailto:' + contacts.email" class="hover:text-accent-400 transition-colors">{{ contacts.email }}</a>
                    <span v-if="(contacts?.phone || contacts?.email)">·</span>
                    <span>© {{ currentYear }}</span>
                    <span>·</span>
                    <a href="/files/RSU%20SP_Privātuma%20politika.pdf" target="_blank" class="hover:text-accent-400 transition-colors">{{ $t('footer.dataUsage') }}</a>
                </div>
                <Link
                    :href="route('login')"
                    class="font-medium text-accent-400 hover:text-accent-300 transition-colors sm:self-end"
                >
                    {{ $t('footer.login') }}
                </Link>
            </div>
        </div>
    </footer>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    name: "Footer",
    components: { Link },
    props: {
        contacts: {
            type: Object,
            default: () => ({})
        },
        scrollToSection: {
            type: Function,
            default: null
        }
    },
    computed: {
        currentYear() {
            return new Date().getFullYear();
        }
    }
};
</script>
