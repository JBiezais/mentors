<template>
    <div class="min-h-screen flex flex-col">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="w-full p-8 flex-grow" style="background-image: url('/img/bg.png'); background-size: cover; background-repeat: no-repeat">
            <div class="rounded-xl bg-gray-100 p-5 space-y-5 text-gray-800 md:max-w-3xl mx-auto">
                <h1 class="text-xl font-semibold">Prieks par Tavu interesi, topošais Mentor!</h1>
                <h1 class="text-xl font-semibold">
                    Aizpildi anketu par sevi, lai zinām, ka var paļauties uz Tevi kā Mentoru! Ievadītie dati tiks izmantoti tikai programmas ietvaros un netiks izpausti trešajām personām.
                </h1>
                <p class="text-gray-500">Prieks par tavu interesi, topošais mentor!</p>
                <p>Atstāj informāciju par sevi, lai zinām, ka varam paļauties uz tevi kā mentoru nākamajā mācību gadā.
                    Ievadītie dati tiks izmantoti tikai programmas ietvaros un netiks izpausti trešajām personām.</p>

                <MentorApplyForm :faculties="faculties"></MentorApplyForm>
            </div>
        </div>
        <Footer></Footer>
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
        faculties: Object
    }
}
</script>

<style scoped>

</style>
