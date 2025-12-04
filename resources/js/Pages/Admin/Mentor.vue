<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow lg:max-w-7xl mx-auto py-5">
            <div class="p-8 bg-gray-50 w-full space-y-5">
                <FilterBar :view="view" :keyword="keyword" :type="type" :program="program" :faculty="faculty" :custom="close" @view="view = $event" @filter="getFilteredProps($event)" @open="close = true" :faculties="faculties">
                    <template v-slot:first>Apstiprinātie</template>
                    <template v-slot:second>Pieteikumi</template>
                </FilterBar>
            </div>
            <div v-if="mentors.length">Kopskaits: {{mentors.length}}</div>
            <div class="border border-gray-300" v-if="view === 'list'">
                <div class="bg-gray-800 text-white font-semibold grid grid-cols-6 p-7">
                    <h1>Vārds Uzvārds</h1>
                    <h1>Telefona numurs</h1>
                    <h1>E-pasts</h1>
                    <h1>Studiju gads</h1>
                    <h1>Fakultāte</h1>
                    <h1>Studiju programa</h1>
                </div>
                <div class="px-7 py-3 border-b border-gray-300 grid grid-cols-6 cursor-pointer" v-for="mentor in mentors" @click="openMentor(mentor.id)">
                    <h1>{{mentor.name}} {{mentor.lastName}}</h1>
                    <h1>{{mentor.phone}}</h1>
                    <h1>{{mentor.email}}</h1>
                    <h1>{{mentor.year}}. gads</h1>
                    <h1>{{findFaculty(mentor.faculty_id)}}</h1>
                    <h1>{{findProgram(mentor.program_id)}}</h1>
                </div>
            </div>
            <div class="space-y-5" v-if="view === 'grid'">
                <div class="border border-gray-300 flex w-full p-5 rounded-xl" v-for="mentor in mentors">
                    <div class="w-1/4">
                        <div class="w-fit h-fit bg-gray-100 rounded-full border-gray-400 shadow-xl p-3 flex">
                            <img class="rounded-full w-48 h-48" :src="'/'+mentor.img" :alt="mentor.name">
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-x-5 gap-y-2 w-3/4 text-gray-800">
                        <div class="flex space-x-5 col-span-4">
                            <Link :href="route('mentor.edit', mentor.id)" class="font-semibold text-xl text-blue-700">{{mentor.name}} {{mentor.lastName}}</Link>
                            <h1 class="my-auto font-semibold">{{mentor.students.length}}/{{mentor.mentees}}</h1>
                            <img src="/img/verified.png" alt="verified" class="w-5 h-5 my-auto" v-if="mentor.status">
                        </div>
                        <h1 class="col-span-1 font-semibold text-lg">Pamatinformācija</h1>
                        <p class="col-span-3">{{mentor.year}}. gads; {{mentor.email}} ; {{mentor.phone}}</p>
                        <h1 class="col-span-1 font-semibold text-lg">Par sevi</h1>
                        <p class="col-span-3">{{mentor.about}}</p>
                        <h1 class="col-span-1 font-semibold text-lg">Iemesli mentorēt</h1>
                        <p class="col-span-3">{{mentor.why}}</p>
                        <h1 class="col-span-1 font-semibold text-lg">Fakultāte</h1>
                        <p class="col-span-3">{{findFaculty(mentor.faculty_id)}}</p>
                        <h1 class="col-span-1 font-semibold text-lg">Studiju programma</h1>
                        <p class="col-span-3">{{findProgram(mentor.program_id)}}</p>
                        <h1 class="col-span-1 font-semibold text-lg">Valodas</h1>
                        <p class="col-span-3"><span v-if="mentor.lv">Latviešu</span> <span v-if="mentor.ru">Krievu</span> <span v-if="mentor.en">Angļu</span></p>
                    </div>
                </div>
            </div>
        </div>
        <Footer :contacts="contacts"/>
        <CustomMail @custom="createCustomMail($event)" @close="close = false" :show="close"></CustomMail>
    </div>
</template>
<style>
    @media (max-width: 486px){
    }
    @media (max-width: 890px){
    }
</style>
<script>
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import { router, Link } from "@inertiajs/vue3";
import FilterBar from "@/Pages/Admin/FilterBar.vue";
import CustomMail from "@/Components/CustomMail.vue";

export default {
    name: "Mentor",
    components: {CustomMail, FilterBar, Header, Footer, Link},
    props:{
        programs: Object,
        mentors: Object,
        faculties: Object,
        keyword: String,
        type: String,
        program: String,
        faculty: String,
        contacts: {
            email: String,
            phone: String
        }
    },

    data(){
        return{
            view: 'grid',
            close: false,
        }
    },

    methods:{
        getFilteredProps($event){
            router.get(route('mentor.index'), $event, {
                preserveState: false
            })
        },
        findFaculty(id){
            return this.faculties.find(faculty => faculty.id === id).title
        },
        findProgram(id){
            return this.programs.find(program => program.id === id).title
        },
        createCustomMail($event){
            let emailForm = {
                content: $event,
                receivers: {
                    type: 'mentors',
                    id: this.mentors.map(mentor => mentor.id)
                }
            }
            router.post(route('sendCustom'), emailForm, {
                preserveState: false
            })
        },
        openMentor(id){
            router.get(route('mentor.edit', id))
        }
    }
}
</script>

