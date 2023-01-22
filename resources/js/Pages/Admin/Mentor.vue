<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow lg:max-w-7xl mx-auto">
            <div class="p-8 bg-gray-50 w-full space-y-5">
                <div class="flex flex-wrap">
                    <div class="flex bg-gray-400 rounded-lg text-gray-100">
                        <div class="flex border-r border-gray-100 px-5 py-3 cursor-pointer" @click="getFilteredProps('all')">
                            <h1 class="my-auto">Visi</h1>
                        </div>
                        <div class="flex border-r border-gray-100 px-5 py-3 cursor-pointer" @click="getFilteredProps('confirmed')">
                            <h1 class="my-auto">Apstiprinātie</h1>
                        </div>
                        <div class="flex px-5 py-3 cursor-pointer" @click="getFilteredProps('requests')">
                            <h1 class="my-auto">Pieteikumi</h1>
                        </div>
                    </div>
                    <div class="flex bg-gray-700 px-5 py-3 rounded-lg text-gray-100 cursor-pointer extraMail">
                        <h1 class="my-auto">Sūtīt ziņu</h1>
                    </div>
                    <form class="w-96 extraSearch">
                        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="search" id="default-search" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-200 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Name, Last Name">
                            <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                        </div>
                    </form>
                </div>
                <div class="flex space-x-3">
                    <h1 class="text-gray-800 my-auto">Fakultātes: </h1>
                    <div class="flex flex-wrap">
                        <div class="bg-gray-200 py-3 px-5 rounded-xl overflow-hidden w-fit ml-3 cursor-pointer">
                            <h1>Visas</h1>
                        </div>
                        <div class="py-3 px-5 rounded-xl overflow-hidden w-fit ml-3 cursor-pointer bg-gray-200 border border-gray-300" v-for="program in programs">
                            <h1>{{program.code}}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="border border-gray-300 flex w-full p-5 rounded-xl cursor-pointer">
                    <div class="w-1/4">
                        <div class="w-fit h-fit bg-gray-100 rounded-full border-gray-400 shadow-xl p-3 flex">
                            <img class="rounded-full w-48 h-48" src="https://mentors.rsu.lv/storage/photos/resized/2022/c6294244a80a85ad60de3c2dd6757ed78b95d95e.jpg" alt="student">
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-x-5 gap-y-2 w-3/4 text-gray-800">
                        <Link :href="route('mentor.edit', 1)" class="col-span-4 font-semibold text-xl text-blue-700">Ance Mistre</Link>
                        <h1 class="col-span-1 font-semibold text-lg">Pamatinformācija</h1>
                        <p class="col-span-3">3. gads; ance.mistre14@gmail.com ; 22024141</p>
                        <h1 class="col-span-1 font-semibold text-lg">Par sevi</h1>
                        <p class="col-span-3">Es sevi raksturoju kā komunikablu, pozitīvu un atvērtu cilvēku! Es vienmēr esmu gatava sniegt palīdzīgu roku ikvienam, kuram tas ir nepieciešams!</p>
                        <h1 class="col-span-1 font-semibold text-lg">Iemesli mentorēt</h1>
                        <p class="col-span-3">Atceros sevi kā pirmā studiju gada studējošo, kura bija ļoti sabijusies un nepārliecināta par to, ko dara, tieši mans mentors bija tas, kurš ievirzīja mani pareizajās sliedēs un iemācīja visu par studiju programmu! Man rūp jaunie studenti un vēlos sniegt savu pieredzi par to kā ir bijis un būt kā atbalstam, kad tas visvairāk nepieciešams!</p>
                        <h1 class="col-span-1 font-semibold text-lg">Fakultāte</h1>
                        <p class="col-span-3">Rehabilitācijas fakultāte</p>
                        <h1 class="col-span-1 font-semibold text-lg">Studiju programma</h1>
                        <p class="col-span-3">Fizioterapija (pilna laika klātiene)</p>
                        <h1 class="col-span-1 font-semibold text-lg">Valodas</h1>
                        <p class="col-span-3">Latviešu</p>
                    </div>
                </div>
            </div>
        </div>
        <Footer></Footer>
    </div>
</template>
<style>
    .extraSearch{
        margin-top: 0;
        margin-left: 1.25rem;
    }
    .extraMail{
        margin-top: 0;
        margin-left: 1.25rem;
    }
    @media (max-width: 486px){
        .extraMail{
            margin-top: 1.25rem;
            margin-left: 0;
        }
    }
    @media (max-width: 890px){
        .extraSearch{
            margin-top: 1.25rem;
            margin-left: 0;
        }
    }
</style>
<script>
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import {Inertia} from "@inertiajs/inertia";
import { Link } from '@inertiajs/vue3';

export default {
    name: "Mentor",
    components: {Header, Footer, Link},
    props:{
      programs: Object,
    },
    data(){
        return{
            filterForm:{
                keyword: '',
                type: 'all'
            }
        }
    },
    methods:{
        getFilteredProps(type = 'all'){
            this.filterForm.type = type
            Inertia.get(route('mentor.index'), this.filterForm, {
                preserveState: false
            })
        }
    }
}
</script>

<style scoped>

</style>
