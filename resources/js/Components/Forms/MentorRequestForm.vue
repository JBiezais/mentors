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
                <div class="border border-gray-800 rounded-xl p-2 space-y-3">
                    <img class="rounded-lg m-auto w-full h-auto" src="https://mentors.rsu.lv/storage/photos/resized/2022/65d0896bc734c3952e2840f5c6bd703c8ff2b0ce.jpg" alt="student">
                    <div class="text-center text-lg">
                        <h1>Georgs Alberts Pimanovs</h1>
                        <p class="text-gray-500 italic text-sm">3. gads</p>
                    </div>
                    <div class="text-center font-semibold text-base md:text-left">
                        Par sevi:
                        <p class="font-medium">
                            Esmu ļoti atvērts un komunikabls students ar kripatiņu entuziasma un radošas domāšanas. Liels kardio un spoguļkameru fanātiķis, tāpēc, tici man, tikai par anatomiskajām struktūrām un histoloģijas protokoliem runāt nespēsim. Aktīvi iesaistos arī universitātes sabiedriskajā dzīvē un problēmu risināšanā kā gada vecākais, tāpēc varēšu izpalīdzēt arī arkārtas situācijās. Ja mīļi palūgsi, droši varēsi uzdot kādu āķīgu jautājumu vakara vēlajās stundās. Teiksim tā - būšu lielais brālis brīžos, kad viss iet greizi!
                        </p>
                    </div>
                    <div class="text-center font-semibold text-base md:text-left">
                        Kāpēc pieteicos mentorēt:
                        <p class="font-medium">
                            Pirmkursnieks noteikti justos daudz pašpārliecinātāks, ja tam būtu kāds stūrakmens, sākot savu akadēmisko “karjeru”, un manuprāt esmu pietiekami labs kandidāts, lai, nevelkot laiku, spētu prasmīgi iemācīt kādam "pirmajam" būt par studentu!
                        </p>
                    </div>
                    <div class="flex text-center font-semibold text-base">
                        Valodas:
                        <h1 class="ml-2 space-x-3 font-medium"><span>Latviešu</span><span>Krievu</span><span>Angļu</span></h1>
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
export default {
    name: "MentorRequestForm",
    props:{
        faculties: Object,
    },
    data(){
        return{
            programs:{},
            form:{
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
            }
        }
    },
    methods:{
        submit(){
            Inertia.post(route('student.store'), this.form,{
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

