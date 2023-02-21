<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow lg:max-w-7xl mx-auto space-y-5 py-5">
            <div>
                <div v-if="!addFaculty" type="submit" class="w-fit text-white bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2" @click="addFaculty = 1">Pievienot fakultāti</div>
                <form class="flex space-x-5" v-if="addFaculty" @submit.prevent="submitFaculty()">
                    <div class=" flex space-x-5" :class="data.length ? 'w-1/2':'min-w-96'">
                        <div class="space-y-3">
                            <h1>Fakultātes nosaukums</h1>
                            <input type="text" v-model="facultyForm.title" class="rounded-lg w-full">
                        </div>
                        <div class="space-y-3">
                            <h1>Fakultātes kods</h1>
                            <input type="text" v-model="facultyForm.code" class="rounded-lg w-full">
                        </div>
                    </div>
                    <div class="mt-auto mb-1">
                        <button type="submit" class="text-white bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Pievienot</button>
                    </div>
               </form>
            </div>
            <div class="space-y-5" v-for="faculty in data">
                <div class="flex space-x-5">
                    <h1 v-if="updateForm.id !== faculty.id" class="text-2xl font-semibold text-gray-800 my-auto">{{faculty.title}}</h1>
                    <form class="space-x-5" v-if="updateForm.id === faculty.id" @submit.prevent="updateFaculty()">
                        <input class="rounded-xl border-2 border-gray-800 text-xl font-semibold text-gray-800" type="text" v-model="updateForm.title">
                        <button class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3">Update</button>
                    </form>
                    <div class="flex space-x-5 h-full py-1.5">
                        <h1 v-if="updateForm.id !== faculty.id" @click="updateForm = faculty" class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3">Labot</h1>
                        <h1 v-if="updateForm.id === faculty.id" @click="updateForm = {id: null, title: ''}" class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3">Aizvērt</h1>
                        <h1 @click="deleteFaculty(faculty.id)" class="cursor-pointer bg-red-600 rounded-lg py-2 px-3 hover:bg-red-800 text-gray-100">Dzēst</h1>
                    </div>
                </div>
                <table class="min-w-full text-center">
                    <thead class="border-b bg-gray-800">
                    <tr>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            Nosaukums
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            Mentoru skaits
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            Mentorējamo skaits
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            Mentorējamo vietas
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            Pāri palikušie
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-white border-b" v-for="program in faculty.programs">
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{program.title}} ({{program.level}})
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{program.mentors_count}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{program.students_count}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{typeof program.spots_total[0] !== 'undefined' ? program.spots_total[0].total_spots: '0'}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap" :class="checkValue(program.students_count, program.spots_total)">
                            {{typeof program.spots_total[0] !== 'undefined' ? program.students_count - program.spots_total[0].total_spots : program.students_count}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap space-x-3 flex">
                            <h1 @click="activeProgram = program" class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3">Labot</h1>
                            <h1 @click="deleteProgram(program.id)" class="cursor-pointer bg-red-600 rounded-lg py-2 px-3 hover:bg-red-800 text-gray-100">Dzēst</h1>
                        </td>
                    </tr>
                    <tr class="bg-white border-b">
                        <td v-if="programForm.faculty_id !== faculty.id" colspan="6" class="cursor-pointer text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap" @click="programForm.faculty_id = faculty.id">
                            <h1>Add new program</h1>
                        </td>
                        <td colspan="6" v-if="programForm.faculty_id === faculty.id" class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <form class="relative space-y-3" @submit.prevent="submitProgram(activeProgram.id)">
                                <div class="w-full">
                                    <input type="text" v-model="programForm.title" placeholder="Nosaukums" class="rounded-lg w-full">
                                </div>
                                <div class="grid grid-cols-3 gap-5">
                                    <input class="rounded-lg w-full mx-auto" type="text" v-model="programForm.level" placeholder="Studiju līmenis">
                                    <input class="rounded-lg w-full" type="text" v-model="programForm.code" placeholder="Kods">
                                </div>
                                <div class="flex w-fit mx-auto space-x-4">
                                    <button v-if="!programForm.id" type="submit" class="text-white bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Pievienot</button>
                                    <button v-if="programForm.id" type="submit" class="text-white bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Update</button>
                                    <h1 @click="activeProgram = {title: '', code: '', lriCode: '', level: '', faculty_id: ''}" type="submit" class="cursor-pointer text-gray-900 bg-gray-100 hover:bg-gray-200 border-gray-900 border focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Aizvērt</h1>
                                </div>
                            </form>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Footer></Footer>
    </div>
</template>

<script>
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import {Link, router} from '@inertiajs/vue3';
import {Inertia} from "@inertiajs/inertia";
export default {
    name: "Program",
    components: {Footer, Header, Link},
    props:{
      data: Object
    },
    data(){
        return{
            activeProgram:{},
            addFaculty: 0,
            createProgram: 0,
            programForm:{
                title: '',
                code: '',
                level: '',
                faculty_id: ''
            },
            facultyForm:{
                title: '',
                code: ''
            },
            updateForm:{
                id: null,
                title: '',
            }
        }
    },
    methods: {
        submitProgram(id){
            if(!this.programForm.id){
                Inertia.post(route('programs.store'), this.programForm, {
                    preserveState: false
                })
            }else{
                Inertia.put(route('programs.update', id), this.programForm, {
                    preserveState: false
                })
            }

        },
        submitFaculty(){
            Inertia.post(route('faculty.store'), this.facultyForm, {
                preserveState: false
            })
        },
        updateFaculty(){
            Inertia.put(route('faculty.update', this.updateForm.id), this.updateForm, {
                preserveState: false
            })
        },
        deleteFaculty(id){
            Inertia.delete(route('faculty.destroy', id),{
                preserveState: false
            })
        },
        deleteProgram(id){
            Inertia.delete(route('programs.destroy', id),{
                preserveState: false,
            })
        },
        checkValue(students, spots){
            if(spots[0]){
                if(spots[0].total_spots < students){
                    return 'bg-red-300'
                }
            }
            return ''
        }
    },
    watch:{
        'activeProgram': function(){
            this.programForm = this.activeProgram
        }
    }
}
</script>
