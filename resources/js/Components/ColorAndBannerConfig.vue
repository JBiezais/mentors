<script>
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import {useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import DropZone from "@/Components/DropZone.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";

export default {
    name: "ColorAndBannerConfig",
    components: {InputError, PrimaryButton, DropZone, TextInput, Modal, SecondaryButton, DangerButton},
    props: {
        banner: {
            type: String,
            default: null
        },
        background: {
            type: String,
            default: null
        },
        color: {
            type: String,
            default: null
        },
    },
    data(){
        return {
            confirmingDataDeletion: false,
            form: useForm({
                color: this.color ?? '#e085f9',
                banner: this.banner ? [this.banner] : [],
                background: this.background ? [this.background] : [],
            })
        }
    },
    methods:{
        submit(){
            this.form.post(route('design'), {
                preserveState: 'errors'
            })
        }
    },
}
</script>

<template>
    <div>
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-5">
            <h1 class="text-2xl font-semibold text-gray-800 my-auto">Akcenta krāsas un Bannera konfigurācija</h1>
            <div class="space-y-5">
                <div class="space-y-3 px-2">
                    <p class="font-semibold">Akcenta krāsa</p>
                    <label for="color" class="space-x-5 flex">
                        <input
                            id="color"
                            v-model="form.color"
                            type="color"
                            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-full shadow-sm h-10 w-10 cursor-pointer"
                        >
                        <span class="cursor-pointer my-auto">{{form.color}}</span>
                    </label>
                </div>
                <div class="space-y-3 px-2">
                    <p class="font-semibold">Banneris (1710x855)</p>
                    <DropZone
                        v-model="form.banner"
                        :allow-multiple-files="false"
                    />
                    <InputError class="mt-2" :message="$page.props.errors.banner" />
                </div>
                <div class="space-y-3 px-2">
                    <p class="font-semibold">Fona tapete (1213x1600)</p>
                    <DropZone
                        v-model="form.background"
                        :allow-multiple-files="false"
                    />
                    <InputError class="mt-2" :message="$page.props.errors.background" />
                </div>
                <PrimaryButton @click="submit">Saglabāt</PrimaryButton>
            </div>
        </div>
    </div>
</template>

<style scoped>
input[type="color"]::-webkit-color-swatch-wrapper {
    padding: 0;
}

input[type="color"]::-webkit-color-swatch {
    border: none;
}
</style>
