<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow lg:max-w-7xl mx-auto py-5">
            <div class="p-8 bg-gray-50 w-full space-y-5">
                <FilterBar :keyword="keyword" :type="type" :program="program" @filter="getFilteredProps($event)" :programs="programs">
                    <template v-slot:first>Apstiprinātie</template>
                    <template v-slot:second>Pieteikumi</template>
                </FilterBar>
            </div>
            <div class="space-y-5">
                <div class="border border-gray-300 flex w-full p-5 rounded-xl" v-for="mentor in mentors">
                    <div class="w-1/4">
                        <div class="w-fit h-fit bg-gray-100 rounded-full border-gray-400 shadow-xl p-3 flex">
                            <img class="rounded-full w-48 h-48" :src="mentor.img" :alt="mentor.name">
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-x-5 gap-y-2 w-3/4 text-gray-800">
                        <Link :href="route('mentor.edit', mentor.id)" class="col-span-4 font-semibold text-xl text-blue-700">{{mentor.name}} {{mentor.lastName}}</Link>
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
        <Footer></Footer>
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
import {Inertia} from "@inertiajs/inertia";
import {Link} from '@inertiajs/vue3';
import FilterBar from "@/Pages/Admin/FilterBar.vue";

export default {
    name: "Mentor",
    components: {FilterBar, Header, Footer, Link},
    props:{
        programs: Object,
        mentors: Object,
        faculties: Object,
        keyword: String,
        type: String,
        program: String
    },

    methods:{
        getFilteredProps($event){
            Inertia.get(route('mentor.index'), $event, {
                preserveState: false
            })
        },
        findFaculty(id){
            return this.faculties.find(faculty => faculty.id === id).title
        },
        findProgram(id){
            return this.programs.find(program => program.id === id).title
        }
    }
}
</script>

