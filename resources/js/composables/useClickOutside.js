import { onMounted, onUnmounted } from 'vue';

export function useClickOutside(targetRef, callback) {
    const handler = (e) => {
        if (targetRef.value && !targetRef.value.contains(e.target)) {
            callback();
        }
    };
    onMounted(() => document.addEventListener('click', handler));
    onUnmounted(() => document.removeEventListener('click', handler));
}
