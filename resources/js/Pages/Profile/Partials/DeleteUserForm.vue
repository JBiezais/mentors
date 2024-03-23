<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Dzēst kontu</h2>

            <p class="mt-1 text-sm text-gray-600">
                Kad jūsu konts tiks dzēsts, visi tā resursi un dati tiks neatgriezeniski izdzēsti. Pirms dzēšat savu kontu, lūdzu, lejupielādējiet jebkurus datus vai informāciju, kuru vēlaties saglabāt.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Dzēst Kontu</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Vai tiešām vēlaties dzēst savu kontu?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Kad jūsu konts tiks dzēsts, visi tā resursi un dati tiks neatgriezeniski izdzēsti. Pirms dzēšat savu kontu, lūdzu, lejupielādējiet jebkurus datus vai informāciju, kuru vēlaties saglabāt.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Parole" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Parole"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Atcelt </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Dzēst kontu
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
