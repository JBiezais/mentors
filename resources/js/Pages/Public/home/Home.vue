<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <div class="w-full sticky top-0 z-50 relative bg-white/70 backdrop-blur-md shadow-sm">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 md:px-8 flex items-center justify-between h-16">
                <a href="#hero" @click.prevent="scrollToSection('hero')" class="flex items-center">
                    <ApplicationLogo class="block h-12 w-auto fill-current text-gray-800" />
                </a>
                <nav class="hidden md:flex items-center gap-6">
                    <a v-if="events?.length" href="#notikumi" @click.prevent="scrollToSection('notikumi')" class="text-gray-700 hover:text-accent-600 font-medium transition-colors">{{ $t('home.nav.notikumi') }}</a>
                    <a href="#uzzini" @click.prevent="scrollToSection('uzzini')" class="text-gray-700 hover:text-accent-600 font-medium transition-colors">{{ $t('home.nav.uzziniVirak') }}</a>
                    <a href="#atsauksmes" @click.prevent="scrollToSection('atsauksmes')" class="text-gray-700 hover:text-accent-600 font-medium transition-colors">{{ $t('home.nav.atsauksmes') }}</a>
                    <a href="#pieteikties" @click.prevent="scrollToSection('pieteikties')" class="inline-flex items-center justify-center px-5 py-2.5 bg-accent-500 hover:bg-accent-600 text-white font-medium rounded-none transition-colors duration-200">{{ $t('home.nav.pieteikties') }}</a>
                    <a :href="route('locale.switch', { locale: $page.props.locale === 'lv' ? 'en' : 'lv' })" class="inline-flex items-center justify-center px-4 py-2.5 border border-accent-500 text-accent-600 hover:bg-accent-50 font-medium rounded-none transition-colors duration-200">
                        {{ $page.props.locale === 'lv' ? $t('home.nav.langLv') : $t('home.nav.langEn') }}
                    </a>
                </nav>
                <div class="md:hidden flex items-center gap-2">
                    <a :href="route('locale.switch', { locale: $page.props.locale === 'lv' ? 'en' : 'lv' })" class="inline-flex items-center justify-center px-4 py-2.5 border border-accent-500 text-accent-600 hover:bg-accent-50 font-medium rounded-none transition-colors duration-200">
                        {{ $page.props.locale === 'lv' ? $t('home.nav.langLv') : $t('home.nav.langEn') }}
                    </a>
                    <button
                        @click="showMobileNav = !showMobileNav"
                        class="p-2 rounded-md text-gray-600 hover:bg-gray-100"
                    >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="!showMobileNav" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>
                </div>
            </div>
            <div v-show="showMobileNav" class="md:hidden border-t border-gray-200 py-4 px-4 sm:px-6 space-y-2">
                <a v-if="events?.length" href="#notikumi" @click="scrollToSection('notikumi'); showMobileNav = false" class="block py-2 text-gray-700 font-medium">{{ $t('home.nav.notikumi') }}</a>
                <a href="#uzzini" @click="scrollToSection('uzzini'); showMobileNav = false" class="block py-2 text-gray-700 font-medium">{{ $t('home.nav.uzziniVirak') }}</a>
                <a href="#atsauksmes" @click="scrollToSection('atsauksmes'); showMobileNav = false" class="block py-2 text-gray-700 font-medium">{{ $t('home.nav.atsauksmes') }}</a>
                <a href="#pieteikties" @click="scrollToSection('pieteikties'); showMobileNav = false" class="block w-full text-center py-3 px-4 bg-accent-500 hover:bg-accent-600 text-white font-medium rounded-none transition-colors duration-200">{{ $t('home.nav.pieteikties') }}</a>
            </div>
        </div>

        <main class="flex-grow">
            <section id="hero" class="bg-white py-12 md:py-16 lg:py-20 px-4 sm:px-6 md:px-8">
                <div class="max-w-6xl mx-auto">
                    <HeroSection :carousel-images="carouselImages" :scroll-to-section="scrollToSection" />
                </div>
            </section>
            <section id="uzzini" class="bg-stone-50 pt-20 md:pt-24 lg:pt-28 pb-12 md:pb-16 px-4 sm:px-6 md:px-8">
                <div class="max-w-6xl mx-auto">
                    <InformationSection/>
                </div>
            </section>
            <section id="atsauksmes" class="bg-white pt-20 md:pt-24 lg:pt-28 pb-12 md:pb-16 px-4 sm:px-6 md:px-8">
                <div class="max-w-6xl mx-auto">
                    <TestimonialSection/>
                </div>
            </section>
            <section id="pieteikties" class="bg-stone-50 pt-20 md:pt-24 lg:pt-28 pb-12 md:pb-16 px-4 sm:px-6 md:px-8">
                <div class="max-w-6xl mx-auto">
                    <ApplySection :faculties="faculties" :mentors="mentors" @success="showSuccessToast" />
                </div>
            </section>
        </main>

        <Footer :contacts="contacts" :scroll-to-section="scrollToSection" />

        <Toast :show="showToast" :message="toastMessage" :duration="5000" />
    </div>
</template>

<script>
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import HeroSection from "@/Pages/Public/home/sections/Hero/HeroSection.vue";
import InformationSection from "@/Pages/Public/home/sections/Information/InformationSection.vue";
import TestimonialSection from "@/Pages/Public/home/sections/Testimonial/TestimonialSection.vue";
import ApplySection from "@/Pages/Public/home/sections/Apply/ApplySection.vue";
import Toast from "@/Components/Toast.vue";

export default {
    name: "Home",
    components: { PrimaryButton, Modal, Toast, ApplicationLogo, Header, Footer, HeroSection, InformationSection, TestimonialSection, ApplySection },
    props: {
        faculties: Array,
        mentors: Array,
        heroGallery: {
            type: Array,
            default: () => []
        },
        events: Array,
        message: Object,
        contacts: Object
    },
    data() {
        return {
            showMessageModal: false,
            showMobileNav: false,
            showToast: false,
            toastMessage: null
        };
    },
    computed: {
        carouselImages() {
            const paths = this.heroGallery?.filter(Boolean) ?? [];
            if (paths.length) {
                return paths.map((p) => (p.startsWith('/') ? p : `/${p}`));
            }
            return ['/img/banner.png'];
        }
    },
    watch: {
        message: {
            handler(val) {
                this.showMessageModal = !!val;
            },
            immediate: true
        }
    },
    mounted() {
        const hash = window.location.hash?.slice(1);
        if (hash) this.$nextTick(() => this.scrollToSection(hash));
    },
    methods: {
        scrollToSection(sectionId) {
            const el = document.getElementById(sectionId);
            if (el) el.scrollIntoView({ behavior: 'smooth' });
        },
        showSuccessToast(msg) {
            this.toastMessage = msg;
            this.showToast = true;
            setTimeout(() => {
                this.showToast = false;
            }, 5000);
        }
    }
};
</script>
