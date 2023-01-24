<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow lg:max-w-7xl space-y-5 mx-auto">
            <div class="grid grid-cols-4 gap-10">
                <div>
                    <div class="w-fit h-fit bg-gray-100 rounded-full border-gray-400 shadow-xl p-3 flex mx-auto">
                        <img class="rounded-full w-48 h-48" src="https://mentors.rsu.lv/storage/photos/resized/2022/c6294244a80a85ad60de3c2dd6757ed78b95d95e.jpg" alt="student">
                    </div>
                </div>
                <div class="col-span-2 grid grid-cols-4 gap-3">
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
                    <h1 class="col-span-1 font-semibold text-lg">Pieteicās</h1>
                    <p class="col-span-3">{{new Date(mentor.created_at).toLocaleDateString()}} {{new Date(mentor.created_at).toLocaleTimeString()}}</p>
                </div>
                <div class="">
                    <div class="bg-gray-200 shadow-xl rounded-xl p-5 space-y-5 flex flex-col w-full">
                        <h1 class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Labot</h1>
                        <h1 class="cursor-pointer bg-sky-700 hover:bg-sky-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Sūtīt ziņu</h1>
                        <Link :href="'/'" class="cursor-pointer bg-cyan-700 hover:bg-cyan-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Nosūtīt mentorējamo datus</Link>
                        <Link :href="'/'" class="cursor-pointer bg-gray-100 hover:bg-red-800 text-red-700 hover:text-gray-50 border border-red-700 rounded-lg py-2 px-3 w-full text-center">Atsaukt mentorējamos</Link>
                        <h1 @click="deleteMentor(mentor.id)" class="cursor-pointer bg-red-500 hover:bg-red-700 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Dzēst</h1>
                    </div>
                </div>
            </div>
            <div class="xl:mx-12 space-y-5">
                <h1 class="text-xl font-semibold">Mentorējamie</h1>
                <table class="min-w-full text-center">
                    <thead class="border-b bg-gray-800">
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
                            Pieteicās
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-white border-b" v-for="student in mentor.students">
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
        <Footer></Footer>
    </div>
</template>

<script>
import FilterBar from "@/Pages/Admin/FilterBar.vue";
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import {useForm} from "@inertiajs/vue3";
import {Link} from '@inertiajs/vue3';
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "EditMentor",
    components: {Footer, Header, Link},
    props:{
        mentor: Object,
        programs: Object,
        faculties: Object,
    },
    data(){
        return{

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
            Inertia.delete(route('mentor.destroy', id), {
                preserveState: false
            })
        }
    }
}
</script>

<style scoped>

</style>
