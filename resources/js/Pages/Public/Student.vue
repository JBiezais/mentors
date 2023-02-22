<template>
    <div class="min-h-screen flex flex-col">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="w-full p-8 flex-grow" style="background-image: url('/img/Untitled%20design%20(2).png'); background-size: cover; background-repeat: no-repeat">
            <div class="rounded-xl bg-gray-100 p-5 space-y-5 text-gray-800 md:max-w-3xl mx-auto">
                <h1 class="text-2xl font-semibold">Gribu sev mentoru!</h1>
                <p class="text-gray-500">Mentors, students-veckursnieks, palīdzēs Tev iesākt studiju gaitas un adaptēties studiju vidē.</p>
                <p>Atstāj informāciju par sevi, lai Tavs mentors varētu sazināties ar Tevi.</p>

                <MentorRequestForm :faculties="faculties" :mentors="mentors"></MentorRequestForm>
            </div>
        </div>
        <Footer></Footer>
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
        faculties: Object,
        mentors: Object,
    }
}
</script>

<style scoped>

</style>
