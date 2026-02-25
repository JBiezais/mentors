<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div v-if="$page.props.auth.user === null" class="w-full sticky top-0 z-50 relative bg-white/70 backdrop-blur-md shadow-sm">
            <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">
                <a href="#hero" @click.prevent="scrollToSection('hero')" class="flex items-center">
                    <ApplicationLogo class="block h-12 w-auto fill-current text-gray-800" />
                </a>
                <nav class="hidden md:flex items-center gap-6">
                    <a v-if="events?.length" href="#notikumi" @click.prevent="scrollToSection('notikumi')" class="text-gray-700 hover:text-accent-600 font-medium transition-colors">Notikumi</a>
                    <a href="#uzzini" @click.prevent="scrollToSection('uzzini')" class="text-gray-700 hover:text-accent-600 font-medium transition-colors">Uzzini vairāk</a>
                    <a href="#atsauksmes" @click.prevent="scrollToSection('atsauksmes')" class="text-gray-700 hover:text-accent-600 font-medium transition-colors">Atsauksmes</a>
                    <a href="#pieteikties" @click.prevent="scrollToSection('pieteikties')" class="inline-flex items-center justify-center px-5 py-2.5 bg-accent-500 hover:bg-accent-600 text-white font-medium rounded-none transition-colors duration-200">Pieteikties</a>
                </nav>
                <button
                    @click="showMobileNav = !showMobileNav"
                    class="md:hidden p-2 rounded-md text-gray-600 hover:bg-gray-100"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="!showMobileNav" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div v-show="showMobileNav" class="md:hidden border-t border-gray-200 py-4 px-4 space-y-2">
                <a v-if="events?.length" href="#notikumi" @click="scrollToSection('notikumi'); showMobileNav = false" class="block py-2 text-gray-700 font-medium">Notikumi</a>
                <a href="#uzzini" @click="scrollToSection('uzzini'); showMobileNav = false" class="block py-2 text-gray-700 font-medium">Uzzini vairāk</a>
                <a href="#atsauksmes" @click="scrollToSection('atsauksmes'); showMobileNav = false" class="block py-2 text-gray-700 font-medium">Atsauksmes</a>
                <a href="#pieteikties" @click="scrollToSection('pieteikties'); showMobileNav = false" class="block w-full text-center py-3 px-4 bg-accent-500 hover:bg-accent-600 text-white font-medium rounded-none transition-colors duration-200">Pieteikties</a>
            </div>
        </div>
        <Modal :show="showMessageModal" @close="showMessageModal = false">
            <div v-if="message" class="p-6 flex flex-col">
                <div class="space-y-2">
                    <h2 class="font-semibold text-xl text-gray-900">{{ message.title }}</h2>
                    <hr class="border-gray-200">
                </div>
                <p class="my-5 text-gray-700 leading-relaxed">{{ message.text }}</p>
                <hr class="border-gray-200">
                <PrimaryButton class="ml-auto mt-4" @click="message = null">Aizvērt</PrimaryButton>
            </div>
        </Modal>

        <main class="flex-grow">
            <section id="hero" class="py-12 md:py-16 lg:py-20 px-4">
                <div class="max-w-6xl mx-auto">
                    <HeroSection :carousel-images="carouselImages" :scroll-to-section="scrollToSection" />
                </div>
            </section>
            <section id="uzzini" class="pt-20 md:pt-24 lg:pt-28 pb-12 md:pb-16 px-4">
                <div class="max-w-6xl mx-auto">
                    <InformationSection/>
                </div>
            </section>
            <section id="atsauksmes" class="pt-20 md:pt-24 lg:pt-28 pb-12 md:pb-16 px-4">
                <div class="max-w-6xl mx-auto">
                    <TestimonialSection/>
                </div>
            </section>
            <section id="pieteikties" class="pt-20 md:pt-24 lg:pt-28 pb-12 md:pb-16 px-4">
                <div class="max-w-6xl mx-auto">
                    <ApplySection :faculties="faculties" :mentors="mentors" />
                </div>
            </section>
        </main>

        <Footer :contacts="contacts" />
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

export default {
    name: "Home",
    components: { PrimaryButton, Modal, ApplicationLogo, Header, Footer, HeroSection, InformationSection, TestimonialSection, ApplySection },
    props: {
        faculties: Array,
        mentors: Array,
        banner: {
            type: String,
            default: '/img/banner.png'
        },
        color: {
            type: String,
            default: '#e085f9'
        },
        background: {
            type: String,
            default: '/img/bg.jpeg'
        },
        events: Array,
        message: Object,
        contacts: Object
    },
    data() {
        return {
            showMessageModal: false,
            showMobileNav: false
        };
    },
    computed: {
        carouselImages() {
            const images = [];
            if (this.banner) images.push(this.banner);
            if (this.background && this.background !== this.banner) images.push(this.background);
            return images.length ? images : ['/img/banner.png'];
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
        }
    }
};
</script>
