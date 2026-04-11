<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow w-full lg:max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="p-4 sm:p-6 lg:p-8 bg-gray-50 w-full space-y-5">
                <FilterBar :keyword="keyword" :type="type" :program="program" :faculty="faculty" :custom="close" @filter="getFilteredProps($event)" @open="close = true" :faculties="faculties">
                    <template v-slot:first>Apstiprinātie</template>
                    <template v-slot:second>Pieteikumi</template>
                </FilterBar>
            </div>
            <div class="py-5">
                <div v-if="mentors.length" class="text-sm font-medium text-gray-600 mb-3 flex items-center gap-2">
                    <Users :size="16" class="shrink-0" />
                    Kopskaits: {{ mentors.length }}
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                    <div
                        v-for="mentor in mentors"
                        @click="openMentor(mentor.id)"
                        class="bg-white border border-gray-200 rounded-lg p-3 hover:border-gray-300 hover:shadow-sm transition-all cursor-pointer flex flex-col gap-2"
                    >
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-medium text-gray-900 text-sm truncate flex-1 flex items-center gap-1.5">
                                <User :size="14" class="shrink-0 text-gray-400" />
                                {{ mentor.name }} {{ mentor.lastName }}
                            </p>
                            <img v-if="mentor.status" src="/img/verified.png" alt="verified" class="w-4 h-4 shrink-0" />
                        </div>
                        <p class="text-xs text-gray-500 truncate flex items-center gap-1.5">
                            <Mail :size="12" class="shrink-0 text-gray-400" />
                            {{ mentor.email }}
                        </p>
                        <p class="text-xs text-gray-600 flex items-center gap-1.5">
                            <GraduationCap :size="12" class="shrink-0 text-gray-400" />
                            {{ mentor.year }}. gads · {{ findFaculty(mentor.faculty_id) }}
                        </p>
                        <p class="text-xs text-gray-500 flex items-center gap-1.5">
                            <Users :size="12" class="shrink-0 text-gray-400" />
                            {{ mentor.students?.length || 0 }}/{{ mentor.mentees }}
                        </p>
                        <div class="flex gap-2 mt-auto pt-2 border-t border-gray-100">
                            <Link
                                :href="route('mentor.edit', mentor.id)"
                                @click.stop
                                class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded px-2.5 py-1.5 transition-colors"
                            >
                                <Pencil :size="12" />
                                Labot
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer :contacts="contacts"/>
        <CustomMail @custom="createCustomMail($event)" @close="close = false" :show="close"></CustomMail>
    </div>
</template>
<script>
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import { router, Link } from "@inertiajs/vue3";
import FilterBar from "@/Pages/Admin/FilterBar.vue";
import CustomMail from "@/Components/CustomMail.vue";
import { Pencil, User, Mail, GraduationCap, Users } from 'lucide-vue-next';

export default {
    name: "Mentor",
    components: { CustomMail, FilterBar, Header, Footer, Link, Pencil, User, Mail, GraduationCap, Users },
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

