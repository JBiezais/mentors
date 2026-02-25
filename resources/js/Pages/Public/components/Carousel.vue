<template>
    <div class="w-full group relative p-4">
        <div class="absolute top-0 left-0 w-12 h-12 border-t-2 border-l-2 border-accent-500 pointer-events-none z-30"></div>
        <div class="absolute bottom-0 right-0 w-12 h-12 border-b-2 border-r-2 border-accent-500 pointer-events-none z-30"></div>
        <div class="relative overflow-hidden shadow-lg aspect-[4/3] bg-gray-200">
            <img
                v-for="(img, index) in images"
                :key="index"
                :src="img"
                :alt="'Slide ' + (index + 1)"
                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                :class="{ 'opacity-100 z-10': currentSlide === index, 'opacity-0 z-0': currentSlide !== index }"
            />
            <div v-if="images?.length > 1" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none group-hover:pointer-events-auto">
                <button
                    v-for="(_, index) in images"
                    :key="index"
                    @click="currentSlide = index"
                    class="w-2.5 h-2.5 rounded-full transition-colors"
                    :class="currentSlide === index ? 'bg-accent-500' : 'bg-white/70 hover:bg-white'"
                />
            </div>
            <button
                v-if="images?.length > 1"
                @click="prevSlide"
                class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/90 hover:bg-white shadow-md flex items-center justify-center text-gray-700 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none group-hover:pointer-events-auto"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
            <button
                v-if="images?.length > 1"
                @click="nextSlide"
                class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/90 hover:bg-white shadow-md flex items-center justify-center text-gray-700 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none group-hover:pointer-events-auto"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Carousel",
    props: {
        images: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            currentSlide: 0,
            carouselInterval: null
        };
    },
    mounted() {
        if (this.images?.length > 1) {
            this.carouselInterval = setInterval(() => {
                this.currentSlide = (this.currentSlide + 1) % this.images.length;
            }, 5000);
        }
    },
    beforeUnmount() {
        if (this.carouselInterval) clearInterval(this.carouselInterval);
    },
    methods: {
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.images.length;
        },
        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.images.length) % this.images.length;
        }
    }
};
</script>
