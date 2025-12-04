<template>
    <form class="space-y-5 text-gray-800" @submit.prevent="submit()">
        <div class="space-y-5 md:space-y-0 md:flex md:space-x-3">
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
                <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.email">
                <InputError class="mt-2" :message="$page.props.errors.email" />
            </label>
        </div>
        <div>
            <label class="flex flex-col">
                Fakultāte
                <select class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.faculty_id">
                    <option disabled value="default">Izvēlieties Fakultāti</option>
                    <option :value="faculty.id" v-for="faculty in faculties">{{faculty.title}}</option>
                </select>
                <InputError class="mt-2" :message="$page.props.errors.faculty_id" />
            </label>
        </div>

        <div>
            <label class="flex flex-col">
                Studiju Programma
                <select class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.program_id">
                    <option disabled value="default">Izvēlieties Fakultāti</option>
                    <option :value="program.id" v-for="program in programs.programs">{{program.title}} ({{program.level}})</option>
                </select>
                <InputError class="mt-2" :message="$page.props.errors.program_id" />
            </label>
        </div>
        <div class="space-y-3 md:space-y-0 md:flex md:space-x-3">
            <label class="flex flex-col md:w-1/2">
                Studiju gads rudens semestrī
                <select class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.year">
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
                <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="number" min="1" max="5" @input="checkInput()" v-model="form.mentees">
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

        <div class="flex flex-col font-semibold">
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
            <h1>Pievienot Attēlu (attēla izmērs nepārsniedz 1MB)</h1>
            <label class="block border-blue-850 border rounded-xl p-1">
                <img :src="formPhotoPreview" class="border w-2/3 border-none rounded-xl" alt="photo" v-if="formPhotoPreview">
                <span class="sr-only">Choose File</span>
                <input type="file" ref="formPhotoUploadField" @change="updatePhotoPreview" class="block w-full rounded-lg text-sm text-blue-850 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-850 file:text-white hover:file:text-white hover:file:bg-blue-950 file:cursor-pointer"/>
            </label>
            <InputError class="mt-2" :message="$page.props.errors.img" />
        </div>
        <div>
            <label class="flex space-x-2 font-semibold">
                <input class="rounded-xl my-auto" type="checkbox" v-model="form.privacy">
                <p>Piekrītu savu datu apstrādāšanai saskaņā ar datu izmantošanas politiku un esmu iepazinies ar <a class="underline" href="/files/studentiem-mentoru_nolikums.docx">nolikumu</a></p>
            </label>
            <InputError class="mt-2" :message="$page.props.errors.privacy" />
        </div>
        <button class="w-full text-center text-gray-100 bg-blue-850 hover:bg-blue-950 rounded-lg py-3 text-xl font-semibold">Nosūtīt pieteikumu</button>
    </form>
</template>

<script>
import { router, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

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
                privacy: null,
                img: null
            }),
        }
    },
    methods:{
        submit(){
            if(!this.form.privacy){
                this.$page.props.errors.privacy = "This field must be selected"
            }else{
                this.form.post(route('mentor.store'), {
                    preserveState: 'errors'
                })
            }
        },
        updatePhotoPreview(){
            const reader = new FileReader();

            reader.onload = (e) => {
                this.formPhotoPreview = e.target.result;
            };

            this.form.img = this.$refs.formPhotoUploadField.files[0];
            reader.readAsDataURL(this.$refs.formPhotoUploadField.files[0]);
        },
        checkInput(){
            if(/^-?\d+(\.\d+)?$/.test(this.form.mentees)){
                if(this.form.mentees > 5 || this.form.mentees < 1){
                    this.form.mentees = ''
                }
            }else{
                this.form.mentees = ''
            }

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
