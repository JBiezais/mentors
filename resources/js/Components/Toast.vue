<template>
    <Transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div
            v-show="show"
            class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 max-w-md w-full mx-4"
        >
            <div
                class="relative overflow-hidden rounded-lg shadow-lg border border-stone-200 bg-stone-50 px-6 py-4"
            >
                <div
                    :key="progressKey"
                    class="absolute left-0 top-0 bottom-0 bg-stone-150 pointer-events-none toast-progress-fill"
                    :style="{ animationDuration: duration + 'ms' }"
                    aria-hidden="true"
                />
                <div class="relative z-10 flex items-center gap-3">
                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-accent-50 flex items-center justify-center">
                        <Check class="w-5 h-5 text-accent-500" />
                    </div>
                    <p class="text-gray-700 leading-relaxed">
                        {{ message?.text }}
                    </p>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script>
import { Check } from 'lucide-vue-next';

export default {
    name: 'Toast',
    components: { Check },
    props: {
        show: {
            type: Boolean,
            default: false,
        },
        message: {
            type: Object,
            default: null,
        },
        duration: {
            type: Number,
            default: 5000,
        },
    },
    data() {
        return {
            progressKey: 0,
        };
    },
    watch: {
        show(val) {
            if (val) this.progressKey++;
        },
    },
};
</script>

<style scoped>
.toast-progress-fill {
    width: 0%;
    animation: toast-fill linear forwards;
}
@keyframes toast-fill {
    to {
        width: 100%;
    }
}
</style>
