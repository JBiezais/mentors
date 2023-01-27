<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow lg:max-w-7xl space-y-5 mx-auto">
            <div class="grid grid-cols-3 gap-10">
                <form class="col-span-2 grid grid-cols-4 gap-3" @submit.prevent="submitForm(student.id)">
                    <Link :href="route('student.edit', student.id)" class="col-span-4 font-semibold text-xl text-blue-700">{{student.name}} {{student.lastName}}</Link>
                    <h1 class="col-span-1 font-semibold text-lg">Telefona nummurs</h1>
                    <input v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.phone">
                    <p v-if="!edit" class="col-span-3">{{student.phone}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">E-pasts</h1>
                    <input v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.email">
                    <p v-if="!edit" class="col-span-3">{{student.email}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Komentārs</h1>
                    <textarea v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800" type="text" v-model="form.comment"></textarea>
                    <p v-if="!edit" class="col-span-3">{{student.comment}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Fakultāte</h1>
                    <select v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.faculty_id">
                        <option disabled value="default">Izvēlieties Fakultāti</option>
                        <option :value="faculty.id" v-for="faculty in faculties">{{faculty.title}}</option>
                    </select>
                    <p v-if="!edit" class="col-span-3">{{findFaculty(student.faculty_id)}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Studiju programma</h1>
                    <select v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.program_id">
                        <option disabled value="default">Izvēlieties Studiju Programu</option>
                        <option :value="program.id" v-for="program in programs.programs">{{program.title}}</option>
                    </select>
                    <p v-if="!edit" class="col-span-3">{{findProgram(student.program_id)}}</p>
                    <h1 class="col-span-1 font-semibold text-lg">Valodas prefrence</h1>
                    <div v-if="edit" class="col-span-3 grid grid-cols-3 mt-3">
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
                    <p v-if="!edit" class="col-span-3"><span v-if="student.lang === 0">Latviešu</span><span v-if="student.lang === 1">Krievu</span><span v-if="student.lang === 2">Angļu</span></p>
                    <h1 class="col-span-1 font-semibold text-lg">Mentors {{edit ? '('+chooseMentor.length+')' : ''}}</h1>
                    <select v-if="edit" class="col-span-3 border-gray-800 bg-gray-100 rounded-lg text-gray-800 w-full" v-model="form.mentor_id">
                        <option disabled value="default">Izvēlieties Studiju Programu</option>
                        <option :value="mentor.id" v-for="mentor in chooseMentor">{{mentor.name}} {{mentor.lastName}}</option>
                    </select>
                    <Link v-if="!edit" :href="route('mentor.edit', student.mentor.id)" class="col-span-3">{{student.mentor.name}} {{student.mentor.lastName}}</Link>
                    <h1 class="col-span-1 font-semibold text-lg">Pieteicās</h1>
                    <p class="col-span-3">{{new Date(student.created_at).toLocaleDateString()}} {{new Date(student.created_at).toLocaleTimeString()}}</p>
                    <div v-if="edit" class="col-span-4 relative">
                        <button class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3 text-center right-0 absolute">Saglabāt</button>
                    </div>
                </form>
                <div class="">
                    <div class="bg-gray-200 shadow-xl rounded-xl p-5 space-y-5 flex flex-col w-full">
                        <h1 @click="edit = 1; getMentors" class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Labot</h1>
                        <h1 class="cursor-pointer bg-sky-700 hover:bg-sky-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Sūtīt ziņu</h1>
                        <Link :href="'/'" class="cursor-pointer bg-cyan-700 hover:bg-cyan-900 text-gray-100 rounded-lg py-2 px-3 w-full text-center">Nosūtīt mentora datus</Link>
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
        faculties: Object,
        mentors: Object
    },
    data(){
        return{
            chooseMentor:{},
            programs:{},
            edit: 0,
            faculty: null,
            form:{
                id: this.student.id,
                name: this.student.name,
                lastName: this.student.lastName,
                phone: this.student.phone,
                email: this.student.email,
                faculty_id: this.student.faculty_id,
                program_id: this.student.program_id,
                mentor_id: this.student.mentor_id,
                comment: this.student.comment,
                lang: this.student.lang,
            }
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
        },
        setPrograms(){
            this.programs = this.faculties.find(faculty => faculty.id === this.form.faculty_id)
        },
        submitForm(id){
            Inertia.put(route('student.update', id), this.form, {
                preserveState: false,
            })
        },
        getMentors(){
            this.chooseMentor = this.mentors.filter(mentor => {
                if(mentor.faculty_id === this.form.faculty_id){
                    switch (this.form.lang.toString()){
                        case '0':
                            if(mentor.lv){
                                return mentor
                            }
                            break
                        case '1':
                            if(mentor.ru){
                                return mentor
                            }
                            break
                        case '2':
                            if(mentor.en){
                                return mentor
                            }
                            break
                    }
                }
            })
        }
    },
    watch:{
        'form.faculty_id': function(){
            this.setPrograms()
            this.getMentors()
        },
        'form.lang':function(){
            this.getMentors()
        }
    },
    mounted(){
        this.setPrograms()
        this.getMentors()
    }
}
</script>

<style scoped>

</style>
