<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow lg:max-w-7xl xl:mx-auto py-5 w-full mx-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-5">
                    <h1 class="text-2xl font-semibold text-gray-800 my-auto">Izveidot lietotāju</h1>
                    <form class="space-y-5 max-w-xl" @submit.prevent="submitEvent()">
                        <div>
                            <InputLabel for="name" value="Vārds, Uzvārds" />
                            <TextInput
                                id="name"
                                ref="nameInput"
                                type="text"
                                v-model="form.name"
                                class="mt-1 block w-full"
                            />
                            <InputError class="mt-2" :message="$page.props.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="email" value="E-pasts" />
                            <TextInput
                                id="email"
                                ref="emailInput"
                                type="text"
                                v-model="form.email"
                                class="mt-1 block w-full"
                            />
                            <InputError class="mt-2" :message="$page.props.errors.email" />
                        </div>
                        <div>
                            <InputLabel for="phone" value="Tālruņa numurs" />
                            <TextInput
                                id="phone"
                                ref="phoneInput"
                                type="text"
                                v-model="form.phone"
                                class="mt-1 block w-full"
                            />
                            <InputError class="mt-2" :message="$page.props.errors.phone" />
                        </div>
                        <div>
                            <InputLabel for="password" value="Parole" />
                            <TextInput
                                id="password"
                                ref="passwordInput"
                                type="password"
                                v-model="form.password"
                                class="mt-1 block w-full"
                            />
                            <InputError class="mt-2" :message="$page.props.errors.password" />
                        </div>
                        <PrimaryButton :disabled="form.processing">Saglabāt</PrimaryButton>
                    </form>
                </div>
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg space-y-5">
                    <h1 class="text-2xl font-semibold text-gray-800 my-auto">Kontaktinformācija</h1>
                    <table class="min-w-full text-center w-full">
                        <thead class="border-b bg-gray-800">
                            <tr>
                                <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                    Vārds Uzvārds
                                </th>
                                <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                    E-pasts
                                </th>
                                <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                    Tālruņa nummurs
                                </th>
                                <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                                    Izmantot
                                </th>
                                <th class="w-36"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users">
                                <td class="py-4">
                                   {{user.name}}
                                </td>
                                <td class="py-4">
                                    {{user.email}}
                                </td>
                                <td class="py-4">
                                    {{user.phone}}
                                </td>
                                <td class="py-4">
                                    <input
                                        type="checkbox"
                                        disabled
                                        :value="user.id"
                                        :checked="user.use"
                                        class="rounded border-gray-800 text-gray-800 shadow-sm focus:ring-white"
                                    />
                                </td>
                                <td class="relative pr-8">
                                    <div @click="action.id ? action.id = '' : action.id = user.id" class="cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-auto" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                        </svg>
                                    </div>
                                    <div v-if="action.id === user.id" class="absolute left-0 border border-gray-200 rounded-lg bg-white shadow-xl w-32 z-50">
                                        <h1 class="py-2 cursor-pointer border-b border-gray-200 hover:bg-gray-100" @click="useUserForContacts">Izmantot</h1>
                                        <h1 class="py-2 cursor-pointer hover:bg-gray-100 text-red-600" v-if="$page.props.auth.user.id !== user.id" @click="deleteUser">Dzēst</h1>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Footer></Footer>
    </div>
</template>

<script>
import Footer from "@/Components/Footer.vue";
import FilterBar from "@/Pages/Admin/FilterBar.vue";
import Header from "@/Components/Header.vue";
import {Inertia} from "@inertiajs/inertia";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {useForm} from "@inertiajs/vue3";

export default {
    name: "Settings",
    components: {
        Checkbox,
        InputError,
        PrimaryButton,
        TextInput,
        InputLabel,
        Header,
        Footer
    },
    props:{
        users: Object
    },
    data(){
        return {
            form: useForm({
                name: '',
                email: '',
                password: '',
                phone: '',
            }),
            use: this.users.find((user) => user.use)?.id,
            action: useForm({
                id: '',
                _method: 'put'
            }),
        }
    },
    methods:{
        useUserForContacts(){
          this.action.post(route('users.update', this.action.id), {
              preserveState: 'errors'
          })
        },
        deleteUser(){
            this.action.delete(route('users.destroy', this.action.id))
        },
        submitEvent(){
            this.form.post(route('users.store'), {
                preserveState: 'errors'
            })
        },
    },
}
</script>

