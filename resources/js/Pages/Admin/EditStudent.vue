<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow lg:max-w-7xl space-y-5 mx-auto">
            <div class="grid grid-cols-3 gap-10">
                <div class="col-span-2 grid grid-cols-4 gap-3">
                    <Link :href="route('student.edit', student.id)" class="col-span-4 font-semibold text-xl text-blue-700">{{student.name}} {{student.lastName}}</Link>
                    <h1 class="col-span-1 font-semibold text-lg">Telefona nummurs</h1>
                    <p class="col-span-3">{{student.phone}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">E-pasts</h1>
                    <p class="col-span-3">{{student.email}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Komentārs</h1>
                    <p class="col-span-3">{{student.comment}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Fakultāte</h1>
                    <p class="col-span-3">{{findFaculty(student.faculty_id)}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Studiju programma</h1>
                    <p class="col-span-3">{{findProgram(student.program_id)}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Valodas prefrence</h1>
                    <p class="col-span-3"><span v-if="student.lang === 0">Latviešu</span><span v-if="student.lang === 1">Krievu</span><span v-if="student.lang === 2">Angļu</span></p>
                    <h1 class="col-span-1 font-semibold text-lg">Pieteicās</h1>
                    <p class="col-span-3">{{new Date(student.created_at).toLocaleDateString()}} {{new Date(student.created_at).toLocaleTimeString()}}</p>
                </div>
                <div class="">
                    <div class="bg-gray-200 shadow-xl rounded-xl p-5 space-y-5 flex flex-col w-full">
                        <h1 class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Labot</h1>
                        <h1 class="cursor-pointer bg-sky-700 hover:bg-sky-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Sūtīt ziņu</h1>
                        <Link :href="'/'" class="cursor-pointer bg-cyan-700 hover:bg-cyan-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Nosūtīt mentorējamo datus</Link>
                        <Link :href="'/'" class="cursor-pointer bg-gray-100 hover:bg-red-800 text-red-700 hover:text-gray-50 border border-red-700 rounded-lg py-2 px-3 w-full text-center">Atsaukt mentorējamos</Link>
                        <h1 @click="deleteStudent(student.id)" class="cursor-pointer bg-red-500 hover:bg-red-700 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Dzēst</h1>
                    </div>
                </div>
            </div>
        </div>
        <Footer></Footer>
    </div>
</template>

<script>
import {Link} from '@inertiajs/vue3';
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import {Inertia} from "@inertiajs/inertia";
export default {
    name: "EditStudent",
    components:{
        Footer,
        Header,
        Link
    },
    props:{
        student: Object,
        faculties: Object
    },
    data(){
        return{
            faculty: null,
        }
    },
    methods:{
        findFaculty(id){
            this.faculty = this.faculties.find(faculty => faculty.id === id)
            return this.faculty.title
        },
        findProgram(id){
            return this.faculty.programs.find(program => program.id === id).title
        },
        deleteStudent(id){
            Inertia.delete(route('student.destroy', id), {
                preserveState: false
            })
        }
    }
}
</script>

<style scoped>

</style>
