<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow lg:max-w-7xl space-y-5 mx-auto py-5">
            <div class="grid grid-cols-4 gap-10">
                <div>
                    <div class="w-fit h-fit bg-gray-100 rounded-full border-gray-400 shadow-xl p-3 flex mx-auto">
                        <img class="rounded-full w-48 h-48" :src="'/'+mentor.img" alt="student">
                    </div>
                </div>
                <form class="col-span-2 grid grid-cols-4 gap-3" @submit.prevent="submitForm(mentor.id)">
                    <Link :href="route('mentor.edit', mentor.id)" class="col-span-4 font-semibold text-xl text-blue-700">{{mentor.name}} {{mentor.lastName}}</Link>
                    <h1 class="col-span-1 font-semibold text-lg">Gads</h1>
                    <select v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.year">
                        <option value="2">2. gads</option>
                        <option value="3">3. gads</option>
                        <option value="4">4. gads</option>
                        <option value="5">5. gads</option>
                        <option value="6">6. gads</option>
                    </select>
                    <p v-if="!edit" class="col-span-3">{{mentor.year}}. gads</p>
                    <h1 class="col-span-1 font-semibold text-lg">E-pasts</h1>
                    <input v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.email">
                    <p v-if="!edit" class="col-span-3">{{mentor.email}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Telefona nummurs</h1>
                    <input v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.phone">
                    <p v-if="!edit" class="col-span-3">{{mentor.phone}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Par sevi</h1>
                    <textarea v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.about"></textarea>
                    <p v-if="!edit" class="col-span-3">{{mentor.about}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Iemesli mentorēt</h1>
                    <textarea v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.why"></textarea>
                    <p v-if="!edit" class="col-span-3">{{mentor.why}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Fakultāte</h1>
                    <select v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.faculty_id">
                        <option disabled value="default">Izvēlieties Fakultāti</option>
                        <option :value="faculty.id" v-for="faculty in faculties">{{faculty.title}}</option>
                    </select>
                    <p v-if="!edit" class="col-span-3">{{findFaculty(mentor.faculty_id)}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Studiju programma</h1>
                    <select v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.program_id">
                        <option disabled value="default">Izvēlieties Studiju Programu</option>
                        <option :value="program.id" v-for="program in dropDownPrograms.programs">{{program.title}}</option>
                    </select>
                    <p v-if="!edit" class="col-span-3">{{findProgram(mentor.program_id)}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Valodas</h1>
                    <div v-if="edit" class="col-span-3 grid grid-cols-3 mt-3">
                        <label class="flex flex-col mx-auto" @click="form.lv? form.lv = 0: form.lv = 1">
                            Latviešu
                            <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="radio" value="1" v-model="form.lv">
                        </label>
                        <label class="flex flex-col mx-auto" @click="form.ru? form.ru = 0: form.ru = 1">
                            Krievu
                            <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="radio" value="1" v-model="form.ru">
                        </label>
                        <label class="flex flex-col mx-auto" @click="form.en? form.en = 0: form.en = 1">
                            Angļu
                            <input class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" type="radio" value="1" v-model="form.en">
                        </label>
                    </div>
                    <p v-if="!edit" class="col-span-3"><span v-if="mentor.lv">Latviešu</span> <span v-if="mentor.ru">Krievu</span> <span v-if="mentor.en">Angļu</span></p>
                    <h1 class="col-span-1 font-semibold text-lg">Pieteicās</h1>
                    <p class="col-span-3">{{new Date(mentor.created_at).toLocaleDateString()}} {{new Date(mentor.created_at).toLocaleTimeString()}}</p>
                    <div v-if="edit" class="col-span-4 relative">
                        <button class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3 text-center right-0 absolute">Saglabāt</button>
                    </div>
                </form>
                <div class="">
                    <div class="bg-gray-200 shadow-xl rounded-xl p-5 space-y-5 flex flex-col w-full">
                        <h1 @click="edit = 1" class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Labot</h1>
                        <h1 @click="confirmMentor(mentor.id)" v-if="!mentor.status" class="cursor-pointer bg-emerald-700 hover:bg-emerald-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Apstiprināt</h1>
                        <h1 @click="close = 1" class="cursor-pointer bg-sky-700 hover:bg-sky-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Sūtīt ziņu</h1>
                        <h1 @click="sendMenteeData(mentor.id)" class="cursor-pointer bg-cyan-700 hover:bg-cyan-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Nosūtīt mentorējamo datus</h1>
                        <h1 @click="removeMentees(mentor.id)" v-if="mentees.length" class="cursor-pointer bg-gray-100 hover:bg-red-800 text-red-700 hover:text-gray-50 border border-red-700 rounded-lg py-2 px-3 w-full text-center">Atsaukt mentorējamos</h1>
                        <h1 @click="deleteMentor(mentor.id)" class="cursor-pointer bg-red-500 hover:bg-red-700 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Dzēst</h1>
                    </div>
                </div>
            </div>
            <div class="xl:mx-12 space-y-5">
                <h1 class="text-xl font-semibold">Mentorējamie</h1>
                <table class="min-w-full text-center">
                    <thead class="border-b bg-gray-800">
                    <tr>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4"></th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            Vārds, Uzvārds
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            Telefona nummurs
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            E-pasts
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            Pieteicās
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-white border-b" v-for="student in mentor.students">
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 mx-auto" @click="selectMentee(student.id)">
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{student.name}}, {{student.lastName}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{student.phone}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{student.email}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{new Date(student.created_at).toLocaleDateString()}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap space-x-3 flex">
                            <div class="flex-grow"></div>
                            <Link :href="route('student.edit', student.id)" class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3">Labot</Link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Footer :contacts="contacts"/>
    </div>
    <CustomMail @custom="getFilteredProps($event)" @close="close = 0" v-if="close"></CustomMail>
</template>

<script>
import FilterBar from "@/Pages/Admin/FilterBar.vue";
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import { useForm, Link, router } from "@inertiajs/vue3";
import CustomMail from "@/Components/CustomMail.vue";

export default {
    name: "EditMentor",
    components: {CustomMail, Footer, Header, Link},
    props:{
        mentor: Object,
        programs: Object,
        faculties: Object,
        contacts: {
            email: String,
            phone: String
        }
    },
    data(){
        return{
            close: 0,
            dropDownPrograms:{},
            edit: 0,
            form:{
                id: this.mentor.id,
                year: this.mentor.year,
                phone: this.mentor.phone,
                email: this.mentor.email,
                about: this.mentor.about,
                why: this.mentor.why,
                faculty_id: this.mentor.faculty_id,
                program_id: this.mentor.program_id,
                lv: this.mentor.lv,
                ru: this.mentor.ru,
                en: this.mentor.en
            },
            mentees:[]
        }
    },
    methods:{
        findFaculty(id){
            return this.faculties.find(faculty => faculty.id === id).title
        },
        findProgram(id){
            return this.programs.find(program => program.id === id).title
        },
        deleteMentor(id){
            router.delete(route('mentor.destroy', id), {
                preserveState: false
            })
        },
        setPrograms(){
            this.dropDownPrograms = this.faculties.find(faculty => faculty.id === this.form.faculty_id)
            console.log(this.dropDownPrograms)
        },
        submitForm(id){
            router.put(route('mentor.update', id), this.form, {
                preserveState: false,
            })
        },
        removeMentees(id){
            router.post(route('remove.mentees', id), {ids: this.mentees}, {
                preserveState:false
            })
        },
        confirmMentor(id){
            router.post(route('confirm.mentor', id), {}, {
                preserveState: false
            })
        },
        selectMentee(id){
            const index = this.mentees.indexOf(id);
            if (index !== -1) {
                this.mentees.splice(index, 1);
            } else {
                this.mentees.push(id);
            }
        },
        sendMenteeData(id){
            router.post(route('sendMenteesData', id), {}, {
                preserveState: false
            })
        },
        getFilteredProps($event){
            let emailForm = {
                content: $event,
                receivers: {
                    type: 'mentors',
                    id: [this.mentor.id]
                }
            }
            router.post(route('sendCustom'), emailForm, {
                preserveState: false
            })
        },
    },
    watch:{
        'form.faculty_id': function(){
            this.setPrograms()
        }
    },
    mounted(){
        this.setPrograms()
    }
}
</script>

<style scoped>

</style>
