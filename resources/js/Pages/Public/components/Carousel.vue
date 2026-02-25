<template>
    <div class="w-full group relative p-4">
        <div class="absolute top-0 left-0 w-12 h-12 border-t-2 border-l-2 border-accent-500 pointer-events-none z-30"></div>
        <div class="absolute bottom-0 right-0 w-12 h-12 border-b-2 border-r-2 border-accent-500 pointer-events-none z-30"></div>
        <div class="relative overflow-hidden shadow-lg aspect-[4/3] bg-gray-200">
            <Swiper
                v-if="images?.length"
                :modules="modules"
                :slides-per-view="1"
                :space-between="0"
                :loop="images.length > 1"
                :autoplay="images.length > 1 ? { delay: 5000 } : false"
                :pagination="images.length > 1 ? { clickable: true } : false"
                :navigation="images.length > 1"
                :grab-cursor="images.length > 1"
                :keyboard="{ enabled: images.length > 1 }"
                :a11y="{ enabled: images.length > 1 }"
                class="hero-carousel !h-full !w-full"
            >
                <SwiperSlide v-for="(img, index) in images" :key="index" class="!h-full">
                    <img
                        :src="img"
                        :alt="$t('carousel.slideAlt', { index: index + 1 })"
                        class="w-full h-full object-cover"
                    />
                </SwiperSlide>
            </Swiper>
        </div>
    </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination, Navigation, Autoplay, Keyboard, A11y } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

defineProps({
    images: {
        type: Array,
        required: true,
    },
});

const modules = [Pagination, Navigation, Autoplay, Keyboard, A11y];
</script>

<style scoped>
.hero-carousel :deep(.swiper-button-next),
.hero-carousel :deep(.swiper-button-prev) {
    color: theme('colors.accent.600');
    width: 28px;
    height: 28px;
    opacity: 1;
    transition: opacity 0.2s;
}
@media (min-width: 768px) {
    .hero-carousel :deep(.swiper-button-next),
    .hero-carousel :deep(.swiper-button-prev) {
        opacity: 0;
    }
    .group:hover .hero-carousel :deep(.swiper-button-next),
    .group:hover .hero-carousel :deep(.swiper-button-prev) {
        opacity: 1;
    }
}
.hero-carousel :deep(.swiper-button-next::after),
.hero-carousel :deep(.swiper-button-prev::after) {
    font-size: 14px;
}
.hero-carousel :deep(.swiper-pagination) {
    opacity: 1;
    transition: opacity 0.2s;
}
@media (min-width: 768px) {
    .hero-carousel :deep(.swiper-pagination) {
        opacity: 0;
    }
    .group:hover .hero-carousel :deep(.swiper-pagination) {
        opacity: 1;
    }
}
.hero-carousel :deep(.swiper-pagination-bullet-active) {
    background-color: theme('colors.accent.500');
}
</style>
