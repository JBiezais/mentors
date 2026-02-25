<script setup>
import { ref } from 'vue';
import { Camera } from 'lucide-vue-next';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    modelValue: [Object, null],
    label: String,
    error: String,
    accept: { type: String, default: 'image/jpeg,image/png,image/jpg' },
    required: { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue']);

const fileInput = ref(null);
const preview = ref(null);

const updatePreview = () => {
    const file = fileInput.value?.files?.[0];
    if (!file) {
        preview.value = null;
        emit('update:modelValue', null);
        return;
    }
    const reader = new FileReader();
    reader.onload = (e) => { preview.value = e.target.result; };
    reader.readAsDataURL(file);
    emit('update:modelValue', file);
};

const triggerFileSelect = () => fileInput.value?.click();
</script>

<template>
    <div class="flex flex-col">
        <span v-if="label" class="text-sm font-medium text-gray-700 mb-1">{{ label }}<span v-if="required" class="text-red-500 ml-0.5">*</span></span>
        <div
            role="button"
            tabindex="0"
            class="block border-2 border-dashed border-gray-200 rounded-lg p-6 hover:border-accent-300 focus-within:border-accent-500 transition-colors cursor-pointer"
            :class="{ 'border-red-500': error }"
            @click="triggerFileSelect"
            @keydown.enter.space.prevent="triggerFileSelect"
        >
            <img
                v-if="preview"
                :src="preview"
                class="mx-auto max-h-40 rounded-lg object-cover"
                alt="Preview"
            />
            <div v-else class="flex flex-col items-center justify-center text-gray-400">
                <Camera class="w-10 h-10 mb-2" />
                <span class="text-sm">Izvēlieties attēlu (JPG, PNG, max 1MB)</span>
            </div>
            <input
                ref="fileInput"
                type="file"
                :accept="accept"
                class="sr-only"
                @click.stop
                @change="updatePreview"
            />
        </div>
        <InputError class="mt-1" :message="error" />
    </div>
</template>
