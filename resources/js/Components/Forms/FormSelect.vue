<script setup>
import { ref, computed } from 'vue';
import { ChevronDown } from 'lucide-vue-next';
import InputError from '@/Components/InputError.vue';
import { useClickOutside } from '@/composables/useClickOutside';

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    error: String,
    options: Array,
    placeholder: String,
    icon: [Object, Function],
    required: { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue']);

const isOpen = ref(false);
const rootRef = ref(null);
const highlightedIndex = ref(-1);

useClickOutside(rootRef, () => {
    isOpen.value = false;
});

const selectedLabel = computed(() => {
    if (!props.options?.length || props.modelValue === 'default' || props.modelValue === null || props.modelValue === undefined) {
        return null;
    }
    const opt = props.options.find((o) => o.value == props.modelValue || o.value === props.modelValue);
    return opt ? opt.label : null;
});

const displayOptions = computed(() => {
    return props.options || [];
});

const triggerClasses = computed(() => {
    const base = 'w-full flex items-center justify-between text-left border border-gray-200 bg-white rounded-lg text-gray-800 focus:border-accent-500 focus:ring-1 focus:ring-accent-500/20 transition-colors outline-none cursor-pointer py-2.5 min-h-[42px]';
    return props.error ? `${base} border-red-500` : base;
});

const toggle = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        highlightedIndex.value = displayOptions.value.findIndex((o) => o.value == props.modelValue);
        if (highlightedIndex.value < 0) highlightedIndex.value = 0;
    }
};

const select = (opt) => {
    if (opt.disabled) return;
    emit('update:modelValue', opt.value);
    isOpen.value = false;
};

const handleKeydown = (e) => {
    if (!isOpen.value) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggle();
        }
        return;
    }

    if (e.key === 'Escape') {
        e.preventDefault();
        isOpen.value = false;
        return;
    }

    if (e.key === 'Enter') {
        e.preventDefault();
        const opt = displayOptions.value[highlightedIndex.value];
        if (opt && !opt.disabled) select(opt);
        return;
    }

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        highlightedIndex.value = Math.min(highlightedIndex.value + 1, displayOptions.value.length - 1);
        return;
    }

    if (e.key === 'ArrowUp') {
        e.preventDefault();
        highlightedIndex.value = Math.max(highlightedIndex.value - 1, 0);
        return;
    }
};
</script>

<template>
    <div ref="rootRef" class="flex flex-col">
        <label class="flex flex-col">
            <span v-if="label" class="text-sm font-medium text-gray-700 mb-1">{{ label }}<span v-if="required" class="text-red-500 ml-0.5">*</span></span>
            <div class="relative">
                <button
                    type="button"
                    role="combobox"
                    :aria-expanded="isOpen"
                    aria-haspopup="listbox"
                    :aria-activedescendant="isOpen && highlightedIndex >= 0 ? `option-${highlightedIndex}` : undefined"
                    :class="[
                        triggerClasses,
                        icon ? 'pl-10 pr-10' : 'px-3 pr-10'
                    ]"
                    @click="toggle"
                    @keydown="handleKeydown"
                >
                    <component
                        v-if="icon"
                        :is="icon"
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                    />
                    <span :class="{ 'text-gray-400': !selectedLabel }">{{ selectedLabel || placeholder }}</span>
                    <ChevronDown
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none transition-transform"
                        :class="{ 'rotate-180': isOpen }"
                    />
                </button>
                <div
                    v-show="isOpen"
                    role="listbox"
                    class="absolute top-full left-0 right-0 mt-1 py-1 bg-white border border-gray-200 rounded-lg shadow-lg z-50 max-h-60 overflow-auto"
                >
                    <div
                        v-for="(opt, idx) in displayOptions"
                        :key="opt.value"
                        :id="`option-${idx}`"
                        role="option"
                        :aria-selected="opt.value == modelValue"
                        :class="[
                            'px-3 py-2.5 text-left text-sm cursor-pointer transition-colors',
                            opt.disabled ? 'text-gray-400 cursor-not-allowed' : 'text-gray-800 hover:bg-accent-50',
                            opt.value == modelValue ? 'bg-accent-50 text-accent-700 font-medium' : '',
                            idx === highlightedIndex && !opt.disabled ? 'bg-accent-50' : ''
                        ]"
                        @mousedown.prevent.stop="select(opt)"
                        @mouseenter="highlightedIndex = opt.disabled ? highlightedIndex : idx"
                    >
                        {{ opt.label }}
                    </div>
                    <div
                        v-if="displayOptions.length === 0"
                        class="px-3 py-2.5 text-sm text-gray-400"
                    >
                        {{ $t('common.noOptions') }}
                    </div>
                </div>
            </div>
        </label>
        <InputError class="mt-1" :message="error" />
    </div>
</template>
