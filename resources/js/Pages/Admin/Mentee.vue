<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow w-full lg:max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="p-4 sm:p-6 lg:p-8 bg-gray-50 w-full space-y-5">
                <FilterBar :keyword="keyword" :type="type" :program="program" :faculty="faculty" :custom="close" @filter="getFilteredProps($event)" @open="close = 1" :faculties="faculties">
                    <template v-slot:first>Ar mentoru</template>
                    <template v-slot:second>Bez mentora</template>
                </FilterBar>
            </div>
            <div class="py-5">
                <div v-if="students.length" class="text-sm font-medium text-gray-600 mb-3 flex items-center gap-2">
                    <Users :size="16" class="shrink-0" />
                    Kopskaits: {{ students.length }}
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                    <div
                        v-for="student in students"
                        class="bg-white border border-gray-200 rounded-lg p-3 hover:border-gray-300 transition-colors flex flex-col gap-2"
                    >
                        <p class="font-medium text-gray-900 text-sm truncate flex items-center gap-1.5" :title="`${student.name} ${student.lastName}`">
                            <User :size="14" class="shrink-0 text-gray-400" />
                            {{ student.name }} {{ student.lastName }}
                        </p>
                        <p class="text-xs text-gray-500 truncate flex items-center gap-1.5" :title="student.email">
                            <Mail :size="12" class="shrink-0 text-gray-400" />
                            {{ student.email }}
                        </p>
                        <p class="text-xs text-gray-600 truncate flex items-center gap-1.5">
                            <GraduationCap :size="12" class="shrink-0 text-gray-400" />
                            {{ findProgram(student.program_id) }}
                        </p>
                        <p v-if="student.mentor" class="text-xs text-gray-500 truncate flex items-center gap-1.5">
                            <UserCheck :size="12" class="shrink-0 text-gray-400" />
                            {{ student.mentor.name }} {{ student.mentor.lastName }}
                        </p>
                        <div class="flex gap-2 mt-auto pt-2 border-t border-gray-100">
                            <Link
                                :href="route('student.edit', student.id)"
                                class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded px-2.5 py-1.5 transition-colors"
                            >
                                <Pencil :size="12" />
                                Labot
                            </Link>
                            <button
                                type="button"
                                @click="deleteStudent(student.id)"
                                class="inline-flex items-center gap-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded px-2.5 py-1.5 transition-colors"
                            >
                                <Trash2 :size="12" />
                                Dzēst
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer :contacts="contacts"/>
    </div>
    <CustomMail @custom="createCustomMail($event)" @close="close = 0" v-if="close"></CustomMail>
</template>

<script>
import FilterBar from "@/Pages/Admin/FilterBar.vue";
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import { router, Link } from "@inertiajs/vue3";
import CustomMail from "@/Components/CustomMail.vue";
import { Pencil, Trash2, User, Mail, GraduationCap, UserCheck, Users } from 'lucide-vue-next';

export default {
    name: "Mentee",
    components: { CustomMail, Footer, Header, FilterBar, Link, Pencil, Trash2, User, Mail, GraduationCap, UserCheck, Users },
    props:{
        programs: Object,
        faculties: Object,
        students: Object,
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
            close: 0,
        }
    },
    methods:{
        getFilteredProps($event){
            router.get(route('student.index'), $event, {
                preserveState: false
            })
        },
        findProgram(id){
            return this.programs.find(program => program.id === id).title
        },
        deleteStudent(id){
          router.delete(route('student.destroy', id), {
              preserveState: false
          })
        },
        createCustomMail($event){
            let emailForm = {
                content: $event,
                receivers: {
                    type: 'students',
                    id: this.students.map(student => student.id)
                }
            }
            router.post(route('sendCustom'), emailForm, {
                preserveState: false
            })
        },
    }
}
</script>

<style scoped>

</style>
