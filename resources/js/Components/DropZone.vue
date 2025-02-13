<template>
    <div
        class="w-full max-w-lg p-6 border-2 border-dashed rounded-lg transition-colors duration-300 cursor-pointer"
        :class="{ 'border-blue-500 bg-blue-100': isDragging, 'border-gray-300 bg-gray-100': !isDragging }"
        @dragover.prevent="handleDragOver"
        @dragleave="handleDragLeave"
        @drop.prevent="handleDrop"
        @click="triggerFileInput"
    >
        <input
            type="file"
            :multiple="allowMultipleFiles"
            accept="image/*"
            class="hidden"
            ref="fileInput"
            @change="handleFileSelect"
        />

        <div class="text-center" v-if="allowMultipleFiles || images.length === 0">
            <p class="text-gray-700 text-sm">
                Drag & drop {{ allowMultipleFiles ? 'images' : 'an image' }} here or
                <span class="text-blue-500 font-semibold">click to upload</span>
            </p>
        </div>

        <div v-if="images.length" class="mt-4">
            <!-- If multiple files are allowed, display in a grid -->
            <div
                 class="grid gap-3"
                 :class="{
                    'grid-cols-3': allowMultipleFiles,
                    'grid-cols-1': !allowMultipleFiles
                }"
            >
                <div v-for="(image, index) in images" :key="index" class="relative group">
                    <img :src="image" alt="Uploaded image" class="w-full object-cover rounded-lg shadow-md">
                    <button
                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs opacity-0 group-hover:opacity-100 transition"
                        @click.stop="removeImage(index)"
                    >
                        <CloseOutline class="w-3 h-3"/>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CloseOutline from "@/Components/CloseOutline.vue";

export default {
    components: {CloseOutline},
    props: {
        allowMultipleFiles: {
            type: Boolean,
            default: true // Default allows multiple files
        },
        modelValue: {}
    },
    data() {
        return {
            images: this.modelValue,
            isDragging: false
        };
    },
    methods: {
        handleDragOver() {
            this.isDragging = true;
        },
        handleDragLeave() {
            this.isDragging = false;
        },
        handleDrop(event) {
            this.isDragging = false;
            this.processFiles(event.dataTransfer.files);
        },
        handleFileSelect(event) {
            this.processFiles(event.target.files);
        },
        processFiles(files) {
            const selectedFiles = Array.from(files).filter(file => file.type.startsWith("image/"));

            if (!this.allowMultipleFiles) {
                selectedFiles.length = 1;
                this.images = [];
            }

            selectedFiles.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.images.push(e.target.result);
                };
                reader.readAsDataURL(file);
            });

            this.$emit('update:modelValue', selectedFiles)
        },
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        removeImage(index) {
            this.images.splice(index, 1);
        }
    }
};
</script>
