<script setup>
import { computed } from 'vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    type: { type: String, default: 'text' },
    error: String,
    placeholder: String,
    min: [String, Number],
    max: [String, Number],
    icon: [Object, Function],
    required: { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue', 'input']);

const model = computed({
    get: () => props.modelValue,
    set: (val) => emit('update:modelValue', val),
});

const inputClasses = computed(() => {
    const base = 'w-full border border-gray-200 bg-white rounded-lg text-gray-800 focus:border-accent-500 focus:ring-1 focus:ring-accent-500/20 transition-colors outline-none';
    return props.error ? `${base} border-red-500` : base;
});
</script>

<template>
    <label class="flex flex-col">
        <span v-if="label" class="text-sm font-medium text-gray-700 mb-1">{{ label }}<span v-if="required" class="text-red-500 ml-0.5">*</span></span>
        <div class="relative">
            <component
                v-if="icon"
                :is="icon"
                class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
            />
            <input
                v-model="model"
                :type="type"
                :placeholder="placeholder"
                :min="min"
                :max="max"
                :class="[
                    inputClasses,
                    icon ? 'pl-10 pr-3 py-2.5' : 'px-3 py-2.5'
                ]"
                v-bind="$attrs"
                @input="emit('input', $event)"
            />
        </div>
        <InputError class="mt-1" :message="error" />
    </label>
</template>
