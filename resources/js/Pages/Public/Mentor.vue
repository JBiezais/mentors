<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <main class="flex-grow py-12 md:py-16 px-4 sm:px-6 md:px-8">
            <div class="max-w-3xl mx-auto">
                <div class="rounded-2xl bg-white p-6 md:p-8 shadow-md border border-gray-100 border-l-4 border-l-accent-500">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">Gribu būt Mentors!</h1>
                    <p class="text-gray-600 leading-relaxed mb-2">
                        Prieks par Tavu interesi, topošais Mentor!
                    </p>
                    <p class="text-gray-500 text-sm mb-6">
                        Aizpildi anketu par sevi, lai zinām, ka var paļauties uz Tevi kā Mentoru! Ievadītie dati tiks izmantoti tikai programmas ietvaros un netiks izpausti trešajām personām.
                    </p>
                    <MentorApplyForm :faculties="faculties"></MentorApplyForm>
                </div>
            </div>
        </main>
        <Footer :contacts="contacts"/>
    </div>
</template>

<script>
import MentorApplyForm from "@/Components/Forms/MentorApplyForm.vue";
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";

export default {
    name: "Mentor",
    components: { Header, Footer, MentorApplyForm },
    props: {
        faculties: Object,
        contacts: Object
    }
};
</script>
