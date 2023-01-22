<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="flex-grow">
            <div class="w-full bg-gray-900 p-8">
                <div class="lg:max-w-4xl mx-auto">
                    <div class="rounded-xl bg-white p-5 space-y-5">
                        <h1 class="text-xl font-semibold text-gray-800">Sveiks, RSU pirmkursniek!</h1>
                        <p class="text-gray-800">Vai arī tev ir radušies jautājumi par studiju procesu, ikdienas gaitām un ārpusstudiju dzīvi Rīgas Stradiņa universitātē? Tad piesakies Mentoram un jau pa visam drīz iegūsi ne tikai atbildes, bet arī paziņu no vecākiem studiju gadiem!</p>
                        <hr class="bg-gray-800">
                        <p class="text-gray-800">Mentors palīdzēs Tev iejusties universitātē, spert pirmos soļus studijās un kāpt vēl augstāk pa karjeras kāpnēm, dodot tik ļoti nepieciešamos padomus. Atbildes uz neatbildētiem jautājumiem, virziens, vietas un lietas, kā arī labs padoms un dzīves triki - to visu varēsi iegūt no mentora. Sākt visu no nulles ir vienkāršāk, ja kāds sniedz palīdzīgu roku! Tādēļ izvēlies savu mentoru un jau drīz tiekamies universitātes gaiteņos!</p>
                        <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-5 md:justify-center">
                            <Link :href="route('student.create')" class="py-5 px-8 bg-red-600 hover:bg-red-700 text-gray-200 hover:text-white shadow-xl flex rounded-xl mx-auto md:mx-0 cursor-pointer">
                                <h1 class="text-xl font-semibold my-auto">Pieteikties mentoram</h1>
                            </Link>
                            <Link :href="route('mentor.create')" class="py-5 px-4 bg-gray-600 hover:bg-gray-800 text-gray-200 hover:text-white shadow-xl rounded-xl mx-auto md:mx-0 cursor-pointer">
                                <h1>Gribu būt mentors</h1>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-8 bg-gray-50">
                <div class="lg:max-w-4xl space-y-5 mx-auto">
                    <div class="w-full space-y-3 md:flex md:space-y-0 md:space-x-5">
                        <div class="md:my-auto">
                            <div class="w-fit h-fit bg-gray-100 rounded-full border-gray-400 shadow-xl p-3 flex mx-auto">
                                <img class="rounded-full m-auto" src="https://mentors.rsu.lv/images/dins-mironovs.jpg" alt="student">
                            </div>
                        </div>
                        <div class="md:w-2/3 md:flex md:flex-col md:justify-center md:space-y-3">
                            <p class="text-gray-800 text-center md:text-right">Mentoru programmā piedalījos, jo gribēju pamēģināt, kā ir būt mentoram. Pašam pirmajā studiju gadā bija neoficiāls mentors caur draugu draugiem, tādēļ arī nolēmu pieteikties, jo visiem šādas iespējas dabūt neoficiālus mentorus nav. Tomēr pirmā studiju gada studējošajiem ir vērtīgi iesaistīties programmā, jo tā var daudz vieglāk un ātrāk iekļauties universitātes vidē, noskaidrot sev interesējošos jautājumus par studiju procesu, akadēmiskajām lietām, pašpārvaldi, tādēļ ļoti silti iesaku pieteikties mentoru programmai.</p>
                            <p class="text-gray-400 text-sm md:text-right">- Dins Mironovs, Medicīnas fakultāte, 3.studiju gads, RSU SP Akadēmiskā virziena vadītāja vietnieks</p>
                        </div>
                    </div>
                    <hr class="bg-gray-800">
                    <div class="w-full space-y-3 md:flex md:flex-row-reverse md:space-y-0 md:space-x-5">
                        <div class="md:my-auto">
                            <div class="w-fit h-fit bg-gray-100 rounded-full border-gray-400 shadow-xl p-3 flex mx-auto">
                                <img class="rounded-full m-auto" src="https://mentors.rsu.lv/images/arta-hermane.jpg" alt="student">
                            </div>
                        </div>

                        <div class="md:w-2/3 md:flex md:flex-col md:justify-center md:space-y-3">
                            <p class="text-gray-800 text-center md:text-left">Mentoru programma noteikti ir lieliska iespēja, lai kvalitatīvi uzsāktu savus pirmos soļus universitātē. Mentori studējošajiem sniedz informācijas apmaiņu, superīgus ieteikumus par studiju procesu, ikdienu universitātē un ir atbalsta plecs jebkurā citā jautājumā. Pieteikties mentoru programmai ir lietderīgi, jo tieši caur mentoru Tev būs iespēja izprast savas programmas pamatprincipus, noskaidrot jebkādus neskaidros jautājumus un Tev būs cilvēks, kurš vienmēr būs gatavs palīdzēt. Es noteikti būtu gatava šogad pieteikties par mentoru, jo šī programma man ļoti daudz palīdzēja un deva iespēju iepazīt fantastisku cilvēku. Un noteikti vēlētos sniegt pretī to pašu kādam pirmkursniekam.</p>
                            <p class="text-gray-400 text-sm md:text-left">- Arta Hermane, Sabiedrības veselības un sociālās labklājības fakultāte, fakultātes domniece</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer></Footer>
    </div>
</template>

<script>
import Footer from "@/Components/Footer.vue";
import { Link } from '@inertiajs/vue3';
import Header from "@/Components/Header.vue";


export default {
    name: "Home",
    components: {Header, Link, Footer},
    methods:{

    }
}
</script>

<style scoped>

</style>
