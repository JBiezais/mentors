<template>
    <div class="min-h-screen flex flex-col">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="w-full p-8 flex-grow" :style="{backgroundImage: `url('${this.getImageUrl(background)}')`, backgroundSize: 'cover', backgroundRepeat: 'no-repeat'}">
            <div class="rounded-xl bg-gray-100 p-5 space-y-5 text-gray-800 md:max-w-3xl mx-auto">
                <h1 class="text-2xl font-semibold">Gribu sev Mentoru!</h1>
                <p class="text-gray-500">
                    Vecāku studiju gadu studējošais jeb Mentors palīdzēs Tev iejusties studiju vidē un veiksmīgi uzsākt studijas! Aizpildi anketu par sevi, lai Mentors varētu ar Tevi sazināties! Ievadītie dati tiks izmantoti tikai programmas ietvaros un netiks izpausti trešajām personām.
                </p>

                <MentorRequestForm :faculties="faculties" :mentors="mentors"></MentorRequestForm>
            </div>
        </div>
        <Footer :contacts="contacts"/>
    </div>

</template>

<script>
import Footer from "@/Components/Footer.vue";
import MentorRequestForm from "@/Components/Forms/MentorRequestForm.vue";
import Header from "@/Components/Header.vue";

export default {
    name: "Student",
    components: {Header, MentorRequestForm, Footer},
    props:{
        background: {
            type: String,
            default: '/img/bg.jpeg'
        },
        faculties: Object,
        mentors: Object,
        contacts: {
            email: String,
            phone: String
        }
    },
    methods: {
        getImageUrl(imagePath)
        {
            return window.Laravel.baseUrl+imagePath
        },
    }
}
</script>

<style scoped>

</style>
