<template>
    <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-center">
        <!-- Image Carousel (left on desktop) -->
        <div class="w-full lg:w-1/2">
            <Carousel :images="carouselImages" />
        </div>
        <!-- Text Content (right on desktop) -->
        <div class="w-full lg:w-1/2 space-y-8 md:space-y-10">
            <h2 class="text-3xl md:text-4xl lg:text-[2.75rem] font-normal text-gray-900 leading-[1.15] tracking-tight">
                Savienojam pieredzējušos <br>
                <span class="text-accent-600 italic">ar jaunajiem.</span>
            </h2>
            <p class="text-base md:text-lg text-gray-600 leading-relaxed max-w-prose">
                Kopā veidojam veiksmīgu studiju ceļu. Mentoru programma ir vieta, kur pieredzējuši studējošie dalās ar zināšanām un pieredzi ar pirmā studiju gada studējošajiem.
            </p>
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 pt-6 md:pt-8">
                <a
                    href="#pieteikties"
                    @click.prevent="scrollToSection('pieteikties')"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-accent-500 hover:bg-accent-600 text-white font-normal rounded-none transition-colors duration-200"
                >
                    Pieteikties programmā
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
                <a
                    href="https://www.rsu.lv/form/arvalstu-studentu-mentora-pietei"
                    target="_blank"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-transparent hover:bg-gray-50 text-gray-900 hover:text-accent-600 font-normal rounded-none transition-colors duration-200"
                >
                    Mentors ārvalstu studējošajiem
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import Carousel from "@/Pages/Public/components/Carousel.vue";

export default {
    name: "HeroSection",
    components: { Carousel },
    props: {
        carouselImages: {
            type: Array,
            required: true
        },
        scrollToSection: {
            type: Function,
            required: true
        }
    }
};
</script>
