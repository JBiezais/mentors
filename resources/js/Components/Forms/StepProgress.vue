<script setup>
import { Check } from 'lucide-vue-next';

defineProps({
    steps: {
        type: Array,
        default: () => [
            { id: 1, label: 'Personīgie dati' },
            { id: 2, label: 'Studijas' },
            { id: 3, label: 'Par Tevi' },
            { id: 4, label: 'Papildinformācija' },
            { id: 5, label: 'Apstiprinājums' },
        ],
    },
    currentStep: { type: Number, required: true },
});
</script>

<template>
    <div class="w-full mb-8">
        <div class="flex items-start">
            <template v-for="(step, index) in steps" :key="step.id">
                <div class="flex flex-col items-center flex-shrink-0">
                    <div
                        :class="[
                            'w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium transition-colors shrink-0',
                            index + 1 < currentStep
                                ? 'bg-accent-500 text-white'
                                : index + 1 === currentStep
                                    ? 'border-2 border-accent-500 bg-white text-accent-600'
                                    : 'border border-gray-300 bg-white text-gray-400',
                        ]"
                    >
                        <Check v-if="index + 1 < currentStep" class="w-5 h-5" />
                        <span v-else>{{ step.id }}</span>
                    </div>
                    <span
                        :class="[
                            'text-xs mt-2 font-medium text-center block w-[5.5rem]',
                            index + 1 <= currentStep ? 'text-gray-700' : 'text-gray-400',
                        ]"
                    >
                        {{ step.label }}
                    </span>
                </div>
                <div
                    v-if="index < steps.length - 1"
                    class="flex-1 h-0.5 mx-1 sm:mx-2 mt-5 min-w-[8px] bg-gray-200 rounded overflow-hidden"
                >
                    <div
                        :class="[
                            'h-full rounded transition-all duration-500 ease-out',
                            index + 1 < currentStep ? 'bg-accent-500 w-full' : 'w-0',
                        ]"
                    />
                </div>
            </template>
        </div>
    </div>
</template>
