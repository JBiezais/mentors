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
        <label class="flex flex-col">
            Komentāri
            <textarea class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.comment"></textarea>
            <InputError class="mt-2" :message="$page.props.errors.comment" />
        </label>
        <div class="flex flex-col">
            Kurā valodā vēlies runāt ar mentoru?
            <div class="grid grid-cols-3 mt-3">
                <label class="flex flex-col mx-auto">
                    Latviešu
                    <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="radio" v-model="form.lang" value="0">
                </label>
                <label class="flex flex-col mx-auto">
                    Krievu
                    <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="radio" v-model="form.lang" value="1">
                </label>
                <label class="flex flex-col mx-auto">
                    Angļu
                    <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="radio" v-model="form.lang" value="2">
                </label>
                <InputError class="mt-2" :message="$page.props.errors.lang" />
            </div>
        </div>
        <div class="text-center md:text-left text-xl font-semibold" v-if="form.faculty_id !== 'default' && form.program_id !== 'default' && form.lang">
            Izvēlies mentoru:
            <div class="md:grid md:grid-cols-2 md:gap-5 mt-5">
                <div class="col-span-2">
                    <label class="flex text-base space-x-5 items-center" @click="form.mentor_id? form.mentor_id = null: 'disabled'">
                        <h1>Jebkurš mentors</h1>
                        <input type="checkbox" class="rounded-full bg-gray-900" :checked="!form.mentor_id">
                    </label>
                </div>
                <div class="rounded-xl p-2 space-y-3" :class="form.mentor_id === mentor.id ? 'border-2 border-emerald-800 shadow-xl': 'border border-gray-800'" @click="addMentor(mentor.id)" v-for="mentor in displayMentors">
                    <img class="rounded-lg m-auto w-full h-auto" :src="'/'+mentor.img" alt="student">
                    <div class="text-center text-lg">
                        <h1>{{mentor.name}} {{mentor.lastName}}</h1>
                        <p class="text-gray-500 italic text-sm">{{mentor.year}}. gads</p>
                    </div>
                    <div class="text-center font-semibold text-base md:text-left">
                        Par sevi:
                        <p v-if="showFullAbout !== mentor.id" @click="showFullAbout = mentor.id" class="font-medium">
                            {{mentor.about.length > 200 ? mentor.about.substring(0, 200) + '...' : mentor.about}}
                        </p>
                        <p v-if="showFullAbout === mentor.id" @click="showFullAbout = null" class="font-medium">
                            {{mentor.about}}
                        </p>
                    </div>
                    <div class="text-center font-semibold text-base md:text-left">
                        Kāpēc pieteicos mentorēt:
                        <p v-if="showFullWhy !== mentor.id" @click="showFullWhy = mentor.id" class="font-medium">
                           {{mentor.why.length > 200 ? mentor.why.substring(0, 200) + '...' : mentor.why}}
                        </p>
                        <p v-if="showFullWhy === mentor.id" @click="showFullWhy = null" class="font-medium">
                            {{mentor.why}}
                        </p>
                    </div>
                    <div class="flex text-center font-semibold text-base">
                        Valodas:
                        <h1 class="ml-2 space-x-3 font-medium"><span v-if="mentor.lv">Latviešu</span><span v-if="mentor.ru">Krievu</span><span v-if="mentor.en">Angļu</span></h1>
                    </div>
                </div>

            </div>
            <InputError class="mt-2" :message="$page.props.errors.mentor" />
        </div>
        <div>
            <label class="flex space-x-2">
                <input class="rounded-xl my-auto" type="checkbox" v-model="form.privacy">
                <p>Piekrītu savu datu apstrādāšanai saskaņā ar datu izmantošanas politiku</p>
            </label>
            <InputError class="mt-2" :message="$page.props.errors.privacy" />
        </div>
        <button class="w-full text-center text-gray-100 bg-blue-850 hover:bg-blue-950 rounded-lg py-3 text-xl font-semibold">Nosūtīt pieteikumu</button>
    </form>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import { useForm } from '@inertiajs/vue3'
import InputError from "@/Components/InputError.vue";
export default {
    name: "MentorRequestForm",
    components: {InputError},
    props:{
        faculties: Object,
        mentors: Object
    },
    data(){
        return{
            showFullAbout:null,
            showFullWhy:null,
            programs:{},
            displayMentors:{},
            form: useForm({
                name: '',
                lastName: '',
                phone: '',
                email: '',
                faculty_id: 'default',
                program_id: 'default',
                comment: '',
                lang: null,
                mentor_id: '',
                privacy: 0
            })
        }
    },
    methods:{
        submit(){
            this.form.post(route('student.store'), {
                preserveState: 'errors'
            })
        },
        addMentor(id){
            if(this.form.mentor === id){
                this.form.mentor_id = null
            }else{
                this.form.mentor_id = id
            }
        },
        filterMentors(){
            this.form.mentor_id = null
            this.displayMentors = this.mentors.filter(mentor => {
                if(mentor.faculty_id === this.form.faculty_id){
                    switch (this.form.lang){
                        case '0':
                            if(mentor.lv){
                                if(mentor.mentees > mentor.students_count){
                                    return mentor
                                }
                            }
                            break
                        case '1':
                            if(mentor.ru){
                                if(mentor.mentees > mentor.students_count){
                                    return mentor
                                }
                            }
                            break
                        case '2':
                            if(mentor.en){
                                if(mentor.mentees > mentor.students_count){
                                    return mentor
                                }
                            }
                            break
                    }
                }
            })
        }
    },
    watch:{
        'form.faculty_id': function(){
            this.programs = this.faculties.find(faculty => faculty.id === this.form.faculty_id)
            this.filterMentors()
        },
        'form.lang': function (){
            this.filterMentors()
        }
    }
}
</script>

