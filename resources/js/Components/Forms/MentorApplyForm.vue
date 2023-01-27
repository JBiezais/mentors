<template>
    <form class="space-y-8 text-gray-800" @submit.prevent="submit()">
        <div class="space-y-5 md:space-y-0 md:flex md:space-x-3">
            <div class="w-1/2">
                <div class="border border-gray-800 rounded-lg relative w-full">
                    <label class="absolute -top-3 left-5 bg-gray-100 px-4 text-emerald-800 font-semibold">
                        Vārds
                    </label>
                    <input class="border-0 rounded-lg bg-gray-100 w-full" type="text" v-model="form.name">
                </div>
                <InputError class="mt-2" :message="$page.props.errors.name" />
            </div>
            <div class="w-1/2">
                <div class="border border-gray-800 rounded-lg relative w-full">
                    <label class="absolute -top-3 left-5 bg-gray-100 px-4 text-emerald-800 font-semibold">
                        Uzvārds
                    </label>
                    <input class="border-0 rounded-lg bg-gray-100 w-full" type="text" v-model="form.lastName">
                </div>
                <InputError class="mt-2" :message="$page.props.errors.lastName" />
            </div>
        </div>
        <div class="space-y-3 md:space-y-0 md:flex md:space-x-3">
            <div class="w-1/2">
                <div class="border border-gray-800 rounded-lg relative w-full">
                    <label class="absolute -top-3 left-5 bg-gray-100 px-4 text-emerald-800 font-semibold">
                        Telefona nummurs
                    </label>
                    <input class="border-0 rounded-lg bg-gray-100 w-full" type="text" v-model="form.phone">
                </div>
                <InputError class="mt-2" :message="$page.props.errors.phone" />
            </div>
            <div class="w-1/2">
                <div class="border border-gray-800 rounded-lg relative w-full">
                    <label class="absolute -top-3 left-5 bg-gray-100 px-4 text-emerald-800 font-semibold">
                        E-pasts
                    </label>
                    <input class="border-0 rounded-lg bg-gray-100 w-full" type="text" v-model="form.email">
                </div>
                <InputError class="mt-2" :message="$page.props.errors.email" />
            </div>
        </div>
        <div>
            <div class="border border-gray-800 rounded-lg relative w-full">
                <label class="absolute -top-3 left-5 bg-gray-100 px-4 text-emerald-800 font-semibold">
                    Fakultāte
                </label>
                <select class="border-0 rounded-lg bg-gray-100 w-full text-sm" v-model="form.faculty_id">
                    <option disabled value="default">Izvēlieties Fakultāti</option>
                    <option :value="faculty.id" v-for="faculty in faculties">{{faculty.title}}</option>
                </select>
            </div>
            <InputError class="mt-2" :message="$page.props.errors.faculty_id" />
        </div>

        <div>
            <div class="border border-gray-800 rounded-lg relative w-full">
                <label class="absolute -top-3 left-5 bg-gray-100 px-4 text-emerald-800 font-semibold">
                    Studiju Programma
                </label>
                <select class="border-0 rounded-lg bg-gray-100 w-full text-sm" v-model="form.program_id">
                    <option disabled value="default">Izvēlieties Studiju Programu</option>
                    <option :value="program.id" v-for="program in programs.programs">{{program.title}}</option>
                </select>
            </div>
            <InputError class="mt-2" :message="$page.props.errors.program_id" />
        </div>
        <div class="space-y-3 md:space-y-0 md:flex md:space-x-3">
            <div class="w-1/2">
                <div class="border border-gray-800 rounded-lg relative w-full">
                    <label class="absolute -top-3 left-5 bg-gray-100 px-4 text-emerald-800 font-semibold">
                        Studiju gads
                    </label>
                    <select class="border-0 rounded-lg bg-gray-100 w-full" v-model="form.year">
                        <option value="2">2. gads</option>
                        <option value="3">3. gads</option>
                        <option value="4">4. gads</option>
                        <option value="5">5. gads</option>
                        <option value="6">6. gads</option>
                    </select>
                </div>
                <InputError class="mt-2" :message="$page.props.errors.year" />
            </div>
            <div class="w-1/2">
                <div class="border border-gray-800 rounded-lg relative w-full">
                    <label class="absolute -top-3 left-5 bg-gray-100 px-4 text-emerald-800 font-semibold">
                        Mentorējamo skaits
                    </label>
                    <input class="border-0 rounded-lg bg-gray-100 w-full" type="number" min="1" max="5" v-model="form.mentees">
                </div>
                <InputError class="mt-2" :message="$page.props.errors.mentees" />
            </div>
        </div>
        <div>
            <div class="border border-gray-800 rounded-lg relative w-full">
                <label class="absolute -top-3 left-5 bg-gray-100 px-4 text-emerald-800 font-semibold">
                    Par Tevi
                </label>
                <textarea class="border-0 rounded-lg bg-gray-100 w-full mb-0 pb-0" type="text" v-model="form.about"></textarea>
            </div>
            <InputError class="mt-2" :message="$page.props.errors.about" />
        </div>

        <div>
            <div class="border border-gray-800 rounded-lg relative w-ful">
                <label class="absolute -top-3 left-5 bg-gray-100 px-4 text-emerald-800 font-semibold">
                    Kāpēc gribi būt mentors?
                </label>
                <textarea class="border-0 rounded-lg bg-gray-100 w-full" type="text" v-model="form.why"></textarea>
            </div>
            <InputError class="mt-2" :message="$page.props.errors.why" />
        </div>

        <div class="flex flex-col text-center font-semibold">
            Brīvi runā
            <div class="grid grid-cols-3 mt-3">
                <label class="flex flex-col mx-auto font-semibold">
                    Latviešu
                    <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="checkbox" v-model="form.lv">
                    <InputError class="mt-2" :message="$page.props.errors.lv" />
                </label>
                <label class="flex flex-col mx-auto font-semibold">
                    Krievu
                    <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="checkbox" v-model="form.ru">
                    <InputError class="mt-2" :message="$page.props.errors.ru" />
                </label>
                <label class="flex flex-col mx-auto font-semibold">
                    Angļu
                    <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="checkbox" v-model="form.en">
                    <InputError class="mt-2" :message="$page.props.errors.en" />
                </label>
            </div>
        </div>
        <div>
            <h1>Pievienot Attēlu</h1>
            <label class="block border-emerald-900 border-2 rounded-xl p-1 space-y-2">
                <img :src="formPhotoPreview" class="border w-2/3 border-none rounded-xl" alt="photo" v-if="formPhotoPreview">
                <span class="sr-only">Choose File</span>
                <input type="file" ref="formPhotoUploadField" @change="updatePhotoPreview" class="block w-full rounded-lg text-sm text-emerald-900 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-500 file:text-emerald-900 hover:file:text-emerald-500 hover:file:bg-emerald-900"/>
            </label>
            <InputError class="mt-2" :message="$page.props.errors.img" />
        </div>
        <div>
            <label class="flex space-x-2 font-semibold">
                <input class="rounded-xl my-auto" type="checkbox" v-model="form.privacy">
                <p>Piekrītu savu datu apstrādāšanai saskaņā ar datu izmantošanas politiku</p>
            </label>
            <InputError class="mt-2" :message="$page.props.errors.privacy" />
        </div>
        <button class="w-full text-center text-gray-100 bg-emerald-800 hover:bg-emerald-900 rounded-lg py-3 text-xl font-semibold">Nosūtīt pieteikumu</button>
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
            formPhotoPreview: null,
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
                privacy: 0,
                img: null
            }),
        }
    },
    methods:{
        submit(){
            this.form.post(route('mentor.store'), {
                preserveState: 'errors'
            })
        },
        updatePhotoPreview(){
            const reader = new FileReader();

            reader.onload = (e) => {
                this.formPhotoPreview = e.target.result;
            };

            this.form.img = this.$refs.formPhotoUploadField.files[0];
            reader.readAsDataURL(this.$refs.formPhotoUploadField.files[0]);
        },
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
