<template>
    <footer class="w-full bg-gray-900 text-gray-300 border-t border-gray-800">
        <div class="max-w-6xl mx-auto px-4 py-12 md:py-16">
            <div class="flex flex-col md:flex-row gap-8 md:gap-12 md:items-center md:justify-between">
                <div class="space-y-4">
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-400">Koordinē</h4>
                    <a href="https://sp.rsu.lv/" target="_blank" class="block">
                        <img class="w-full max-w-[200px] h-auto" src="/img/white-footer-logo.png" alt="RSUsp">
                    </a>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <a href="tel:+37127750555" class="hover:text-accent-400 transition-colors">+371 27 750 555</a>
                        <a href="mailto:sp@rsu.lv" class="hover:text-accent-400 transition-colors">sp@rsu.lv</a>
                    </div>
                </div>
                <div class="flex flex-col md:items-end gap-4">
                    <a href="/files/RSU%20SP_Privātuma%20politika.pdf" target="_blank" class="text-sm hover:text-accent-400 transition-colors">
                        Datu izmantošana
                    </a>
                    <div>
                        <p class="text-sm font-semibold text-gray-400 mb-2">Jautājumu gadījumā zvani vai raksti:</p>
                        <div class="flex flex-col gap-1 font-medium">
                            <a v-if="contacts?.phone" :href="'tel:' + contacts.phone" class="hover:text-accent-400 transition-colors">
                                {{ contacts.phone }}
                            </a>
                            <a v-if="contacts?.email" :href="'mailto:' + contacts.email" class="hover:text-accent-400 transition-colors">
                                {{ contacts.email }}
                            </a>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a
                            href="https://www.instagram.com/rsusp/"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-gray-800 hover:bg-accent-600 flex items-center justify-center transition-colors"
                        >
                            <img src="/img/instagram.svg" alt="Instagram" class="w-5 h-5">
                        </a>
                        <a
                            href="https://www.facebook.com/RSUSP"
                            target="_blank"
                            class="w-10 h-10 rounded-full bg-gray-800 hover:bg-accent-600 flex items-center justify-center transition-colors"
                        >
                            <img src="/img/facebook.svg" alt="Facebook" class="w-5 h-5">
                        </a>
                    </div>
                    <Link
                        v-if="$page.props.auth.user === null"
                        :href="route('login')"
                        class="text-sm font-medium text-accent-400 hover:text-accent-300 transition-colors"
                    >
                        Autorizēties
                    </Link>
                </div>
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
        }
    }
};
</script>
