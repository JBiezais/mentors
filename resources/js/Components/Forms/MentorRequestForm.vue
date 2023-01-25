<template>
    <form class="space-y-3 text-gray-800" @submit.prevent="submit()">
        <div class="space-y-3 md:space-y-0 md:flex md:space-x-3">
            <label class="flex flex-col md:w-1/2">
                Vārds
                <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.name">
            </label>
            <label class="flex flex-col md:w-1/2">
                Uzvārds
                <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.lastName">
            </label>
        </div>
        <div class="space-y-3 md:space-y-0 md:flex md:space-x-3">
            <label class="flex flex-col md:w-1/2">
                Telefona nummurs
                <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.phone">
            </label>
            <label class="flex flex-col md:w-1/2">
                E-pasts
                <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="email" v-model="form.email">
            </label>
        </div>
        <label class="flex flex-col">
            Fakultāte
            <select class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.faculty_id">
                <option disabled value="default">Izvēlieties Fakultāti</option>
                <option :value="faculty.id" v-for="faculty in faculties">{{faculty.title}}</option>
            </select>
        </label>
        <label class="flex flex-col">
            Studiju Programma
            <select class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.program_id">
                <option disabled value="default">Izvēlieties Studiju Programu</option>
                <option :value="program.id" v-for="program in programs.programs">{{program.title}}</option>
            </select>
        </label>
        <label class="flex flex-col">
            Komentāri
            <textarea class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.comment"></textarea>
        </label>
        <div class="flex flex-col text-center">
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
            </div>
        </div>
        <div class="text-center md:text-left text-xl font-semibold">
            Izvēlies mentoru:
            <div class="md:grid md:grid-cols-2 md:gap-5 mt-5">
                <div class="rounded-xl p-2 space-y-3" :class="form.mentor === mentor.id ? 'border-2 border-emerald-800 shadow-xl': 'border border-gray-800'" @click="addMentor(mentor.id)" v-for="mentor in displayMentors">
                    <img class="rounded-lg m-auto w-full h-auto" src="https://mentors.rsu.lv/storage/photos/resized/2022/65d0896bc734c3952e2840f5c6bd703c8ff2b0ce.jpg" alt="student">
                    <div class="text-center text-lg">
                        <h1>{{mentor.name}} {{mentor.lastName}}</h1>
                        <p class="text-gray-500 italic text-sm">{{mentor.year}}. gads</p>
                    </div>
                    <div class="text-center font-semibold text-base md:text-left">
                        Par sevi:
                        <p class="font-medium">
                            {{mentor.about}}
                        </p>
                    </div>
                    <div class="text-center font-semibold text-base md:text-left">
                        Kāpēc pieteicos mentorēt:
                        <p class="font-medium">
                           {{mentor.why}}
                        </p>
                    </div>
                    <div class="flex text-center font-semibold text-base">
                        Valodas:
                        <h1 class="ml-2 space-x-3 font-medium"><span v-if="mentor.lv">Latviešu</span><span v-if="mentor.ru">Krievu</span><span v-if="mentor.en">Angļu</span></h1>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <label class="flex space-x-2">
                <input class="rounded-xl my-auto" type="checkbox" v-model="form.privacy">
                <p>Piekrītu savu datu apstrādāšanai saskaņā ar datu izmantošanas politiku</p>
            </label>
        </div>
        <button class="w-full text-center text-gray-100 bg-green-600 hover:bg-green-700 rounded-lg py-3 text-xl font-semibold">Nosūtīt pieteikumu</button>
    </form>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import { useForm } from '@inertiajs/vue3'
export default {
    name: "MentorRequestForm",
    props:{
        faculties: Object,
        mentors: Object
    },
    data(){
        return{
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
                mentor: '',
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
                this.form.mentor = null
            }else{

                this.form.mentor = id
            }
        },
        filterMentors(){
            this.displayMentors = this.mentors.filter(mentor => {
                if(mentor.program_id === this.form.program_id){
                    switch (this.form.lang){
                        case '0':
                            if(mentor.lv){
                                return mentor
                            }
                            break
                        case '1':
                            if(mentor.ru){
                                return mentor
                            }
                            break
                        case '2':
                            if(mentor.en){
                                return mentor
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
        },
        'form.program_id': function(){
            this.filterMentors()
        },
        'form.lang': function (){
            this.filterMentors()
        }
    }
}
</script>

