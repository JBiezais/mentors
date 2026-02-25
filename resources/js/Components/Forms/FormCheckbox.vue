<script setup>
import { computed } from 'vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    modelValue: [Boolean, Array, Number],
    value: { default: null },
    label: String,
    error: String,
    required: { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue']);

const isChecked = computed({
    get() {
        if (Array.isArray(props.modelValue)) {
            return props.modelValue.includes(props.value);
        }
        return Boolean(props.modelValue);
    },
    set(val) {
        if (props.value !== null && props.value !== undefined) {
            if (Array.isArray(props.modelValue)) {
                const arr = [...props.modelValue];
                if (val) arr.push(props.value);
                else arr.splice(arr.indexOf(props.value), 1);
                emit('update:modelValue', arr);
            } else {
                emit('update:modelValue', val ? 1 : 0);
            }
        } else {
            emit('update:modelValue', val ? true : false);
        }
    },
});
</script>

<template>
    <label class="flex flex-col">
        <div class="flex items-start gap-2">
            <input
                v-model="isChecked"
                type="checkbox"
                class="mt-1 rounded border-gray-300 text-accent-500 focus:ring-accent-500/20 focus:ring-2 focus:ring-offset-0 w-4 h-4 cursor-pointer"
            />
            <span v-if="label" class="text-sm font-medium text-gray-700 flex-1" v-html="label"></span><span v-if="required && label" class="text-red-500 ml-0.5">*</span>
        </div>
        <InputError class="mt-1" :message="error" />
    </label>
</template>
