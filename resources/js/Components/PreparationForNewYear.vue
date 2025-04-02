<script>
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import {useForm} from "@inertiajs/vue3";

export default {
    name: "PreparationForNewYear",
    components: {Modal, SecondaryButton, DangerButton},
    data(){
        return {
            confirmingDataDeletion: false,
            emptyForm: useForm({})
        }
    },
    methods:{
        archiveData(){
            this.emptyForm.post(route('archive'), {
                preserveState: 'errors'
            })
        }
    },
}
</script>

<template>
    <div>
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-5">
            <h1 class="text-2xl font-semibold text-gray-800 my-auto">Sagatavošanās jaunam gadam</h1>
            <div class="grid grid-cols-2">
                <div class="space-y-5">
                    <p class="text-lg">Veicot sagatavošanos jaunam gadam tiks <span class="font-bold text-yellow-600">arhivēti</span> un nebūs pieejami admin panelī</p>
                    <ul class="pl-8 list-disc">
                        <li>Mentori</li>
                        <li>Studenti</li>
                    </ul>
                    <p class="text-lg">Veicot sagatavošanos jaunam gadam tiks <span class="font-bold text-red-600">dzēsti</span></p>
                    <ul class="pl-8 list-disc">
                        <li>Izsūtītie e-pasti</li>
                    </ul>
                </div>
                <div class="flex">
                    <DangerButton class="m-auto" @click="confirmingDataDeletion = true">Sagatavoties jaunam gadam</DangerButton>
                </div>
            </div>
        </div>
        <Modal :show="confirmingDataDeletion" @close="confirmingDataDeletion = false">
            <div class="p-6">
                <div class="space-y-5">
                    <h2 class="text-xl font-bold font-medium text-gray-900">
                        Vai tiešām vēlaties sagatavoties jaunam gadam?
                    </h2>
                    <p class="text-lg">Veicot sagatavošanos jaunam gadam tiks <span class="font-bold text-yellow-600">arhivēti</span> un nebūs pieejami admin panelī</p>
                    <ul class="pl-8 list-disc">
                        <li>Mentori</li>
                        <li>Studenti</li>
                    </ul>
                    <p class="text-lg">Veicot sagatavošanos jaunam gadam tiks <span class="font-bold text-red-600">dzēsti</span></p>
                    <ul class="pl-8 list-disc">
                        <li>Izsūtītie e-pasti</li>
                    </ul>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="confirmingDataDeletion = false"> Atcelt </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': emptyForm.processing }"
                        :disabled="emptyForm.processing"
                        @click="archiveData()"
                    >
                        Sagatavoties jaunam gadam
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </div>
</template>

<style scoped>

</style>
