<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow lg:max-w-7xl mx-auto">
            <div class="p-8 bg-gray-50 w-full space-y-5">
                <FilterBar :keyword="keyword" :type="type" :program="program" :faculty="faculty" :custom="close" @filter="getFilteredProps($event)" @open="close = 1" :faculties="faculties">
                    <template v-slot:first>Ar mentoru</template>
                    <template v-slot:second>Bez mentora</template>
                </FilterBar>
            </div>
            <div class="py-5">
                <table class="min-w-full text-center">
                    <thead class="border-b bg-gray-800 sticky top-0">
                    <tr>
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
                            Studē
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            Mentors
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-white border-b" v-for="student in students">
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
                            {{findProgram(student.program_id)}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{student.mentor ? student.mentor.name : ''}} {{student.mentor ? student.mentor.lastName : ''}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap space-x-3 flex">
                            <div class="flex-grow"></div>
                            <Link :href="route('student.edit', student.id)" class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3">Labot</Link>
                            <h1 @click="deleteStudent(student.id)" class="cursor-pointer bg-red-600 rounded-lg py-2 px-3 hover:bg-red-800 text-gray-100">Dzēst</h1>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Footer></Footer>
    </div>
    <CustomMail @custom="createCustomMail($event)" @close="close = 0" v-if="close"></CustomMail>
</template>

<script>
import FilterBar from "@/Pages/Admin/FilterBar.vue";
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import {Inertia} from "@inertiajs/inertia";
import {Link} from '@inertiajs/vue3';
import CustomMail from "@/Components/CustomMail.vue";

export default {
    name: "Mentee",
    components: {CustomMail, Footer, Header, FilterBar, Link},
    props:{
        programs: Object,
        faculties: Object,
        students: Object,
        keyword: String,
        type: String,
        program: String,
        faculty: String,
    },
    data(){
        return{
            close: 0,
        }
    },
    methods:{
        getFilteredProps($event){
            Inertia.get(route('student.index'), $event, {
                preserveState: false
            })
        },
        findProgram(id){
            return this.programs.find(program => program.id === id).title
        },
        deleteStudent(id){
          Inertia.delete(route('student.destroy', id), {
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
            Inertia.post(route('sendCustom'), emailForm, {
                preserveState: false
            })
        },
    }
}
</script>

<style scoped>

</style>
