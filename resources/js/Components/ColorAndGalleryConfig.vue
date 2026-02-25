<script>
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import { useForm, router } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import DropZone from "@/Components/DropZone.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import draggable from "vuedraggable";
import CloseOutline from "@/Components/CloseOutline.vue";

export default {
    name: "ColorAndGalleryConfig",
    components: {
        InputError,
        PrimaryButton,
        DropZone,
        TextInput,
        Modal,
        SecondaryButton,
        DangerButton,
        draggable,
        CloseOutline,
    },
    props: {
        heroGallery: {
            type: Array,
            default: () => []
        },
        color: {
            type: String,
            default: null
        },
    },
    data() {
        return {
            confirmingDataDeletion: false,
            galleryItems: this.heroGallery.map((item) => ({ ...item })),
            form: useForm({
                color: this.color ?? 'pink',
            })
        };
    },
    computed: {
        colorSchemes() {
            return this.$page.props.accentScheme?.schemes ?? {};
        },
        imageUrl() {
            return (path) => (path.startsWith('/') ? path : `/${path}`);
        }
    },
    watch: {
        heroGallery: {
            handler(val) {
                this.galleryItems = (val || []).map((item) => ({ ...item }));
            },
            immediate: false
        }
    },
    methods: {
        submit() {
            this.form.post(route('design'), {
                preserveState: 'errors'
            });
        },
        onGalleryReorder() {
            const ids = this.galleryItems.map((item) => item.id);
            router.patch(route('config.hero-gallery.reorder'), { ids });
        },
        removeGalleryImage(item) {
            router.delete(route('config.hero-gallery.destroy', item.id));
        },
        onGalleryFilesSelected(files) {
            if (!files?.length) return;
            const formData = new FormData();
            files.forEach((file, i) => formData.append(`images[${i}]`, file));
            router.post(route('config.hero-gallery.store'), formData, {
                forceFormData: true,
                preserveScroll: true,
            });
        }
    },
};
</script>

<template>
    <div>
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-5">
            <h1 class="text-2xl font-semibold text-gray-800 my-auto">Akcenta krāsas un Hero galerijas konfigurācija</h1>
            <div class="space-y-5">
                <div class="space-y-3 px-2">
                    <p class="font-semibold">Akcenta krāsa</p>
                    <div class="flex flex-wrap gap-3">
                        <label
                            v-for="(scheme, key) in colorSchemes"
                            :key="key"
                            class="flex items-center gap-2 cursor-pointer border rounded-lg p-3 transition-colors"
                            :class="form.color === key ? 'ring-2 ring-accent-500 border-accent-500' : 'border-gray-200 hover:border-gray-300'"
                        >
                            <input
                                type="radio"
                                v-model="form.color"
                                :value="key"
                                class="sr-only"
                            >
                            <span
                                class="w-8 h-8 rounded-full shrink-0"
                                :style="{ backgroundColor: scheme['500'] }"
                            ></span>
                            <span class="text-sm font-medium text-gray-700">{{ scheme.label }}</span>
                        </label>
                    </div>
                </div>
                <div class="space-y-3 px-2">
                    <p class="font-semibold">Hero galerija (4:3 ieteicamais attēlu izmērs)</p>
                    <div class="space-y-4">
                        <draggable
                            v-model="galleryItems"
                            item-key="id"
                            handle=".drag-handle"
                            @end="onGalleryReorder"
                            class="flex flex-wrap gap-3"
                        >
                            <template #item="{ element }">
                                <div class="relative group w-24 h-24 shrink-0 rounded-lg overflow-hidden shadow border border-gray-200 bg-gray-100">
                                    <img
                                        :src="imageUrl(element.path)"
                                        :alt="'Gallery image ' + element.id"
                                        class="w-full h-full object-cover"
                                    />
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="drag-handle cursor-grab active:cursor-grabbing p-1 text-white">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 012 2v12a2 2 0 01-4 0V4a2 2 0 012-2zm6 0a2 2 0 012 2v12a2 2 0 01-4 0V4a2 2 0 012-2z"/></svg>
                                        </span>
                                    </div>
                                    <button
                                        type="button"
                                        class="absolute top-1 right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 text-xs transition"
                                        @click="removeGalleryImage(element)"
                                    >
                                        <CloseOutline class="w-3 h-3"/>
                                    </button>
                                </div>
                            </template>
                        </draggable>
                        <div class="mt-2">
                            <DropZone
                                :key="'gallery-add-' + heroGallery.length"
                                :model-value="[]"
                                :allow-multiple-files="true"
                                @update:model-value="onGalleryFilesSelected"
                            />
                        </div>
                    </div>
                    <InputError class="mt-2" :message="$page.props.errors?.images" />
                </div>
                <PrimaryButton @click="submit">Saglabāt</PrimaryButton>
            </div>
        </div>
    </div>
</template>
