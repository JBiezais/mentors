<template>
    <div>
        <h3 class="text-2xl md:text-3xl lg:text-4xl font-medium text-gray-900 tracking-tight mb-12 text-center">{{ $t('testimonial.title') }}</h3>

        <!-- Mobile: Swiper carousel -->
        <div class="md:hidden px-8">
            <TestimonialCarousel :testimonials="testimonials" />
        </div>

        <!-- Desktop: Original stacked layout -->
        <div class="hidden md:block divide-y divide-gray-200">
            <div v-for="testimonial in testimonials" class="flex flex-col md:flex-row gap-8 md:gap-12 py-12 md:py-16 first:pt-0 last:pb-0">
                <div class="flex-shrink-0">
                    <img :src="testimonial.image" :alt="testimonial.alt" class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-none" />
                </div>
                <div class="flex-1 min-w-0 space-y-6">
                    <blockquote class="text-lg md:text-xl text-gray-600 leading-relaxed">{{ $t(testimonial.quoteKey) }}</blockquote>
                    <p class="text-sm text-gray-500">{{ $t(testimonial.attributionKey) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TestimonialCarousel from './TestimonialCarousel.vue';

export default {
    name: "TestimonialSection",
    components: { TestimonialCarousel },
    data() {
        return {
            testimonials: [
                { image: '/img/home/pers_1.jpeg', alt: 'Madara Miller', quoteKey: 'testimonial.quote1', attributionKey: 'testimonial.attribution1' },
                { image: '/img/home/pers_2.jpeg', alt: 'Rolāns Vozņesenskis', quoteKey: 'testimonial.quote2', attributionKey: 'testimonial.attribution2' },
                { image: '/img/home/pers_3.jpeg', alt: 'Veronika Tagijeva', quoteKey: 'testimonial.quote3', attributionKey: 'testimonial.attribution3' },
                { image: '/img/home/pers_4.jpeg', alt: 'Eduards Doroņins', quoteKey: 'testimonial.quote4', attributionKey: 'testimonial.attribution4' },
            ],
        };
    },
};
</script>
