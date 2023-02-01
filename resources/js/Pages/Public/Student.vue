<template>
    <div class="min-h-screen flex flex-col">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="w-full background p-8 flex-grow">
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
.background{
    background-color: #365314;
    background-image: url("data:image/svg+xml,%3Csvg width='70' height='73' viewBox='0 0 42 44' xmlns='http://www.w3.org/2000/svg'%3E%3Cg id='Page-1' fill='none' fill-rule='evenodd'%3E%3Cg id='brick-wall' fill='%236ca81c' fill-opacity='0.23'%3E%3Cpath d='M0 0h42v44H0V0zm1 1h40v20H1V1zM0 23h20v20H0V23zm22 0h20v20H22V23z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
</style>
