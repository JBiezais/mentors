<script setup>

const props = defineProps({
    styleClasses: {
        type: String,
        default: '',
    },
});

</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" :class="styleClasses" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M368 368L144 144M368 144L144 368"/></svg>
</template>
