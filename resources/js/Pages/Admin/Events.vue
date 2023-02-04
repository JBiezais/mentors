<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow lg:max-w-7xl mx-auto py-5 w-full">
            <div class="space-y-5">
                <table class="min-w-full text-center w-full">
                    <thead class="border-b bg-gray-800">
                    <tr>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            Nosaukums
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            Datums
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                            Vieta
                        </th>
                        <th scope="col" class="text-sm font-medium text-white px-6 py-4">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-white border-b" v-for="event in events">
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{event.title}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{event.date}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            {{event.location}}
                        </td>
                        <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap space-x-3 flex">
                            <h1 @click="form = event; addNew = 1" class="cursor-pointer bg-gray-700 hover:bg-gray-900 text-gray-100 rounded-lg py-2 px-3">Labot</h1>
                            <h1 @click="deleteProgram(event.id)" class="cursor-pointer bg-red-600 rounded-lg py-2 px-3 hover:bg-red-800 text-gray-100">Dzēst</h1>
                        </td>
                    </tr>
                    <tr class="bg-white border-b">
                        <td v-if="!addNew" colspan="4" class="cursor-pointer text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap" @click="addNew = 1">
                            <h1>Add new event</h1>
                        </td>
                        <td colspan="6" v-if="addNew" class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                            <form class="relative space-y-3" @submit.prevent="submitEvent()">
                                <div class="grid grid-cols-3 gap-5">
                                    <h1>Nosaukums</h1>
                                    <h1>Datums, Laiks</h1>
                                    <h1>Vieta</h1>
                                    <input type="text" class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.title">
                                    <input type="datetime-local" class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.date">
                                    <input type="text" class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-model="form.location">
                                    <h1>Mentoru apmācības</h1>
                                    <h1>Mentorējamo pieteikšanās</h1>
                                    <div v-if="!form.mentors_training && !form.mentees_applying"></div>
                                    <h1 v-if="form.mentors_training || form.mentees_applying">Links <span v-if="form.mentors_training">URL uz materiāliem</span> <span v-if="form.mentees_applying">URL uz anketu</span></h1>
                                    <input type="radio" value="1" class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 m-auto" @click="form.mentors_training? form.mentors_training = 0: form.mentors_training = 1; form.mentees_applying = 0; form.link = null" v-model="form.mentors_training">
                                    <input type="radio" value="1" class="border-gray-800 bg-gray-100 rounded-lg text-gray-800 m-auto" @click="form.mentees_applying? form.mentees_applying = 0: form.mentees_applying = 1; form.mentors_training = 0; form.link = null" v-model="form.mentees_applying">
                                    <input type="text" class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-if="form.mentors_training || form.mentees_applying" v-model="form.link">
                                    <div v-if="form.mentees_applying" class="col-span-2"></div>
                                    <h1  v-if="form.mentees_applying">Sadaļas nosaukums</h1>
                                    <div v-if="form.mentees_applying" class="col-span-2"></div>
                                    <input type="text" class="border-gray-800 bg-gray-100 rounded-lg text-gray-800" v-if="form.mentees_applying" v-model="form.description">
                                </div>
                                <div class="flex w-fit mx-auto space-x-4">
                                    <button v-if="!form.id" type="submit" class="text-white bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Pievienot</button>
                                    <button v-if="form.id" type="submit" class="text-white bg-gray-700 hover:bg-gray-900 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Update</button>
                                    <h1 @click="form = {title: '', location: '', date: null, description: '', id: null}; addNew=0" type="submit" class="cursor-pointer text-gray-900 bg-gray-100 hover:bg-gray-200 border-gray-900 border focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Aizvērt</h1>
                                </div>
                            </form>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <form  class="flex flex-col">


                </form>
            </div>
        </div>
        <Footer></Footer>
    </div>
</template>

<script>
import Footer from "@/Components/Footer.vue";
import FilterBar from "@/Pages/Admin/FilterBar.vue";
import Header from "@/Components/Header.vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "Events",
    components: {Header, FilterBar, Footer},
    props:{
        events: Object
    },
    data(){
        return {
            addNew: 0,
            upcomingEvents: {},
            pastEvents:{},
            form:{
                title: '',
                date: null,
                location: '',
                description: '',
                mentors_training: 0,
                mentees_applying: 0,
                link: ''
            }
        }
    },
    methods:{
        submitEvent(){
            if(!this.form.id){
                Inertia.post(route('event.store'), this.form, {
                    preserveState: false
                })
            }else{
                Inertia.put(route('event.update', this.form.id), this.form, {
                    preserveState: false
                })
            }

        },
        deleteProgram(id){
            Inertia.delete(route('event.destroy', id), {
                preserveState: false
            })
        }
    },
}
</script>

