<template>
    <form class="space-y-3 text-gray-800" @submit.prevent="submit()">
        <div class="space-y-3 md:space-y-0 md:flex md:space-x-3">
            <label class="flex flex-col md:w-1/2">
                Vārds
                <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.name">
                <InputError class="mt-2" :message="$page.props.errors.name" />
            </label>
            <label class="flex flex-col md:w-1/2">
                Uzvārds
                <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.lastName">
                <InputError class="mt-2" :message="$page.props.errors.lastName" />
            </label>
        </div>
        <div class="space-y-3 md:space-y-0 md:flex md:space-x-3">
            <label class="flex flex-col md:w-1/2">
                Telefona nummurs
                <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.phone">
                <InputError class="mt-2" :message="$page.props.errors.phone" />
            </label>
            <label class="flex flex-col md:w-1/2">
                E-pasts
                <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="email" v-model="form.email">
                <InputError class="mt-2" :message="$page.props.errors.email" />
            </label>
        </div>
        <label class="flex flex-col">
            Fakultāte
            <select class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.faculty_id">
                <option disabled value="default">Izvēlieties Fakultāti</option>
                <option :value="faculty.id" v-for="faculty in faculties">{{faculty.title}}</option>
            </select>
            <InputError class="mt-2" :message="$page.props.errors.faculty_id" />
        </label>
        <label class="flex flex-col">
            Studiju Programma
            <select class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.program_id">
                <option disabled value="default">Izvēlieties Studiju Programu</option>
                <option :value="program.id" v-for="program in programs.programs">{{program.title}}</option>
            </select>
            <InputError class="mt-2" :message="$page.props.errors.program_id" />
        </label>
        <div class="space-y-3 md:space-y-0 md:flex md:space-x-3">
            <label class="flex flex-col md:w-1/2">
                Studiju gads
                <select class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.year">
                    <option value="1">1. gads</option>
                    <option value="2">2. gads</option>
                    <option value="3">3. gads</option>
                    <option value="4">4. gads</option>
                    <option value="5">5. gads</option>
                    <option value="6">6. gads</option>
                </select>
                <InputError class="mt-2" :message="$page.props.errors.year" />
            </label>
            <label class="flex flex-col md:w-1/2">
                Mentorējamo skaits
                <input class="w-full border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="number" min="1" max="5" v-model="form.mentees">
                <InputError class="mt-2" :message="$page.props.errors.mentees" />
            </label>
        </div>
        <label class="flex flex-col">
            Par Tevi
            <textarea class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.about"></textarea>
            <InputError class="mt-2" :message="$page.props.errors.about" />
        </label>
        <label class="flex flex-col">
            Kāpēc gribi būt mentors?
            <textarea class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.why"></textarea>
            <InputError class="mt-2" :message="$page.props.errors.why" />
        </label>
        <div class="flex flex-col text-center">
            Brīvi runā
            <div class="grid grid-cols-3 mt-3">
                <label class="flex flex-col mx-auto">
                    Latviešu
                    <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="checkbox" v-model="form.lv">
                    <InputError class="mt-2" :message="$page.props.errors.lv" />
                </label>
                <label class="flex flex-col mx-auto">
                    Krievu
                    <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="checkbox" v-model="form.ru">
                    <InputError class="mt-2" :message="$page.props.errors.ru" />
                </label>
                <label class="flex flex-col mx-auto">
                    Angļu
                    <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="checkbox" v-model="form.en">
                    <InputError class="mt-2" :message="$page.props.errors.en" />
                </label>
            </div>
        </div>
        <div>
            <label class="flex space-x-2">
                <input class="rounded-xl my-auto" type="checkbox" v-model="form.privacy">
                <p>Piekrītu savu datu apstrādāšanai saskaņā ar datu izmantošanas politiku</p>
            </label>
            <InputError class="mt-2" :message="$page.props.errors.privacy" />
        </div>
        <button class="w-full text-center text-gray-100 bg-green-600 hover:bg-green-700 rounded-lg py-3 text-xl font-semibold">Nosūtīt pieteikumu</button>
    </form>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import InputError from "@/Components/InputError.vue";
import { useForm } from '@inertiajs/vue3'

export default {
    name: "MentorApplyForm",
    components: {InputError},
    props: {
        faculties: Object
    },
    data(){
        return{
            programs: {},
            form: useForm({
                name: '',
                lastName: '',
                phone: '',
                email: '',
                faculty_id: 'default',
                program_id: 'default',
                year: 1,
                mentees: 5,
                about: '',
                why: '',
                lv: 0,
                ru: 0,
                en: 0,
                privacy: 0
            }),
        }
    },
    methods:{
        submit(){
            this.form.post(route('mentor.store'), {
                preserveState: 'errors'
            })
        }
    },
    watch:{
        'form.faculty_id': function(){
            this.programs = this.faculties.find(faculty => faculty.id === this.form.faculty_id)
        }
    }
}
</script>

<style scoped>

</style>
