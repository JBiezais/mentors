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
    name: "Statistics",
}
</script>

<template>
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-5">
        <h1 class="text-2xl font-semibold text-gray-800 my-auto">Statistikas</h1>

        <div class="space-x-5">
            <a
                :href="route('config.statistics', {type: 'facultyMentorMentee'})"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                Mentoru un Mentorējamo sadalījums fakultātēs
            </a>
            <a
                :href="route('config.statistics', {type: 'yearlyParticipant'})"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                Pieteikumi pa gadiem
            </a>
        </div>
    </div>
</template>

<style scoped>

</style>
