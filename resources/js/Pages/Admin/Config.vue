<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow lg:max-w-7xl xl:mx-auto py-5 w-full mx-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <PreparationForNewYear/>
            </div>
        </div>
        <div class="flex-grow lg:max-w-7xl xl:mx-auto py-5 w-full mx-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <ColorAndBannerConfig
                    :color="color"
                    :banner="banner"
                    :background="background"
                />
            </div>
        </div>
        <div class="flex-grow lg:max-w-7xl xl:mx-auto py-5 w-full mx-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <Statistics/>
            </div>
        </div>
        <Footer :contacts="contacts"/>
    </div>
</template>

<script>
import Footer from "@/Components/Footer.vue";
import FilterBar from "@/Pages/Admin/FilterBar.vue";
import Header from "@/Components/Header.vue";
import { router, useForm } from "@inertiajs/vue3";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import Checkbox from "@/Components/Checkbox.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import PreparationForNewYear from "@/Components/PreparationForNewYear.vue";
import ColorAndBannerConfig from "@/Components/ColorAndBannerConfig.vue";
import Statistics from "@/Components/Forms/Statistics.vue";

export default {
    name: "Settings",
    components: {
        Statistics,
        ColorAndBannerConfig,
        PreparationForNewYear,
        Modal, SecondaryButton,
        DangerButton,
        Checkbox,
        InputError,
        PrimaryButton,
        TextInput,
        InputLabel,
        Header,
        Footer
    },
    props:{
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
        contacts: {
            email: String,
            phone: String
        }
    },
}
</script>

