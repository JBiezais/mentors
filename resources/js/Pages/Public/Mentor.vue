<template>
    <div class="min-h-screen flex flex-col">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="w-full p-8 flex-grow" :style="{backgroundImage: `url('${this.getImageUrl(background)}')`, backgroundSize: 'cover', backgroundRepeat: 'no-repeat'}">
            <div class="rounded-xl bg-gray-100 p-5 space-y-5 text-gray-800 md:max-w-3xl mx-auto">
                <h1 class="text-2xl font-semibold">Gribu būt Mentors!</h1>
                <p class="text-gray-500">Prieks par Tavu interesi, topošais Mentor!</p>
                <p>
                    Aizpildi anketu par sevi, lai zinām, ka var paļauties uz Tevi kā Mentoru! Ievadītie dati tiks izmantoti tikai programmas ietvaros un netiks izpausti trešajām personām.
                </p>
                <MentorApplyForm :faculties="faculties"></MentorApplyForm>
            </div>
        </div>
        <Footer :contacts="contacts"/>
    </div>
</template>

<script>
import MentorApplyForm from "@/Components/Forms/MentorApplyForm.vue";
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";

export default {
    name: "Mentor",
    components: {Header, Footer, MentorApplyForm},
    props: {
        background: {
            type: String,
            default: '/img/bg.jpeg'
        },
        faculties: Object,
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
