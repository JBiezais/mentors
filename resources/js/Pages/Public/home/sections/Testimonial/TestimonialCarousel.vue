<template>
    <div class="relative">
        <Swiper
            :modules="[Pagination, Navigation, Keyboard, A11y]"
            :slides-per-view="1"
            :space-between="16"
            :pagination="{ clickable: true }"
            :navigation="true"
            :grab-cursor="true"
            :keyboard="{ enabled: true }"
            :a11y="{ enabled: true }"
            class="testimonial-swiper"
        >
            <SwiperSlide v-for="(t, index) in testimonials" :key="index">
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
                    <img
                        :src="t.image"
                        :alt="t.alt"
                        class="w-36 h-36 object-cover rounded-none mx-auto block mb-6"
                    />
                    <blockquote class="text-base text-gray-600 leading-relaxed">
                        {{ $t(t.quoteKey) }}
                    </blockquote>
                    <p class="text-sm text-gray-500 mt-4">
                        {{ $t(t.attributionKey) }}
                    </p>
                </div>
            </SwiperSlide>
        </Swiper>
    </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination, Navigation, Keyboard, A11y } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

defineProps({
    testimonials: {
        type: Array,
        required: true,
    },
});
</script>

<style scoped>
.testimonial-swiper :deep(.swiper-button-next),
.testimonial-swiper :deep(.swiper-button-prev) {
    color: theme('colors.accent.600');
    width: 28px;
    height: 28px;
}
.testimonial-swiper :deep(.swiper-button-next::after),
.testimonial-swiper :deep(.swiper-button-prev::after) {
    font-size: 14px;
}
.testimonial-swiper :deep(.swiper-pagination-bullet-active) {
    background-color: theme('colors.accent.500');
}
</style>
