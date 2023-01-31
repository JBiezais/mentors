<template>
    <div class="min-h-screen flex flex-col bg-gray-100">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div class="h-20 w-full sticky top-0 z-10 bg-gray-100">
            <div class="lg:max-w-5xl m-auto flex">
                <ApplicationLogo
                    class="block h-16 w-auto my-auto fill-current text-gray-800"
                />
                <div class="m-auto">
                    <h1 class="text-2xl font-semibold uppercase">mentoru programma {{new Date().getFullYear()}}</h1>
                    <h1></h1>
                </div>
            </div>
        </div>

        <div class="flex-grow">

            <div class="w-full background py-8">
<!--                <ApplicationLogo class="w-1/3 h-fit my-auto -left-60 absolute -top-24"></ApplicationLogo>-->
                <div class="lg:max-w-5xl mx-auto flex flex-col md:flex-row space-y-5 md:space-x-8 md:space-y-0">
                    <div class="flex flex flex-col md:flex-row space-y-5 md:space-x-8 md:space-y-0 w-full">
                        <div class="rounded-xl bg-gray-50 p-5 h-fit my-auto" :class="events.length? 'md:w-1/2': 'w-full'">
                            <div class="flex md:justify-center" :class="events.length? 'flex-col space-y-8': 'space-x-4'">
                                <Link :href="route('student.create')" class="py-5 uppercase px-8 border-4 border-lime-700 hover:bg-lime-700 text-lime-700 hover:text-white shadow-xl rounded-xl mx-auto md:mx-0 cursor-pointer w-full">
                                    <h1 class="text-xl text-center font-bold my-auto">Pieteikties mentoram</h1>
                                </Link>
                                <Link :href="route('mentor.create')" class="py-5 px-4 uppercase border-4 border-lime-700 hover:bg-lime-700 text-lime-700 hover:text-white shadow-xl rounded-xl mx-auto md:mx-0 cursor-pointer w-full">
                                    <h1 class="text-xl text-center my-auto font-bold" >Gribu būt mentors</h1>
                                </Link>
                                <div class="py-5 px-8 uppercase border-4 border-lime-700 text-center hover:bg-lime-700 text-lime-700 hover:text-white shadow-xl rounded-xl mx-auto md:mx-0 cursor-pointer text-xl font-bold my-auto w-full">
                                    <a class="text-center">Ārzemju mentors</a>
                                </div>
                            </div>
                        </div>
                        <div class="md:w-1/2 rounded-xl bg-gray-50 p-5 h-fit" v-if="events.length">
                            <div class="space-y-4 md:justify-center">
                                <h1 class="text-2xl text-center text-lime-700 uppercase font-bold">Gaidāmie notikumi</h1>
                                <div class="space-y-4 overflow-y-auto h-96 pb-4">
                                    <div v-for="event in events" class="flex space-x-5 border border-gray-300 rounded-xl shadow-lg p-1">
                                        <div class="py-3 px-4 rounded-xl">
                                            <h1 class="text-3xl text-center font-black -my-1">{{new Date(event.date).getDate()}}</h1>
                                            <h1 class="text-xs text-center uppercase">{{new Date(event.date).toLocaleDateString("lv-LV", { month: "short" })}}</h1>
                                        </div>
                                        <div class="w-2/3 flex flex-col my-auto">
                                            <h1>{{event.title}}</h1>
                                            <div class="flex space-x-4">
                                                <div class="flex space-x-2">
                                                    <img src="/img/clock.svg" alt="location" class="h-4 w-auto my-auto">
                                                    <h1 class="my-auto">{{new Date(event.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}}</h1>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <img src="/img/location.svg" alt="location" class="h-4 w-auto my-auto">
                                                    <h1 class="my-auto">{{event.location}}</h1>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-8 bg-gray-50">
                <div class="lg:max-w-5xl space-y-5 mx-auto">
                    <div class="flex flex-col space-y-5">
                        <div class="relative">
                            <div class="card">
                                <div class="md:w-3/4 bg-gray-100 shadow-xl rounded-xl flex front">
                                    <div class="w-3 rounded-l-xl bg-yellow-500 h-auto"></div>
                                    <div class="p-5">
                                        <h1 class="text-2xl">Mentors</h1>
                                        <p class="text-xl italic ">Mentors ir kā sienas, kas balsta jumtu.</p>
                                    </div>
                                </div>
                                <div class="md:w-3/4 bg-gray-100 shadow-xl rounded-xl flex back">
                                    <div class="p-5 w-fit">
                                        <h1 class="text-2xl">Mentors</h1>
                                        <p class="text-xl italic ">Mentors ir kā sienas, kas balsta jumtu.</p>
                                    </div>
                                    <div class="flex-grow"></div>
                                    <div class="w-3 rounded-r-xl bg-lime-700 h-auto"></div>
                                </div>
                            </div>
                        </div>
                        <div class="relative flex">
                            <div class="flex-grow"></div>
                            <div class="card">
                                <div class="md:w-3/4 bg-gray-100 shadow-xl rounded-xl flex front">
                                    <div class="p-5 w-fit">
                                        <h1 class="text-2xl">Mentors</h1>
                                        <p class="text-xl italic ">Mentors ir kā sienas, kas balsta jumtu.</p>
                                    </div>
                                    <div class="flex-grow"></div>
                                    <div class="w-3 rounded-r-xl bg-yellow-500 h-auto"></div>
                                </div>
                                <div class="md:w-3/4 bg-gray-100 shadow-xl rounded-xl flex back">
                                    <div class="w-3 rounded-l-xl bg-lime-700 h-auto"></div>
                                    <div class="p-5">
                                        <h1 class="text-2xl">Mentors</h1>
                                        <p class="text-xl italic ">Dažreiz viss, kas vajadzīgs, lai spertu pirmos soļus, ir nedaudz atbalsta.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="card">
                                <div class="md:w-3/4 bg-gray-100 shadow-xl rounded-xl flex front">
                                    <div class="w-3 rounded-l-xl bg-yellow-500 h-auto"></div>
                                    <div class="p-5">
                                        <h1 class="text-2xl">Mentors</h1>
                                        <p class="text-xl italic ">Viss nezināmais var šķists biedējošs. Cik labi, ja kāds palīdz to padarīt par zināmu.</p>
                                    </div>
                                </div>
                                <div class="md:w-3/4 bg-gray-100 shadow-xl rounded-xl flex back">
                                    <div class="p-5 w-fit">
                                        <h1 class="text-2xl">Mentors</h1>
                                        <p class="text-xl italic ">Mentors ir kā sienas, kas balsta jumtu.</p>
                                    </div>
                                    <div class="flex-grow"></div>
                                    <div class="w-3 rounded-r-xl bg-lime-700 h-auto"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-8 bg-gray-50">
                <div class="lg:max-w-5xl space-y-5 mx-auto">
                    <div class="w-full space-y-3 md:flex md:space-y-0 md:space-x-5">
                        <div class="md:my-auto">
                            <div class="w-fit h-fit bg-yellow-500 rounded-full border-gray-400 shadow-xl p-3 flex mx-auto">
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
                            <div class="w-fit h-fit bg-yellow-500 rounded-full border-gray-400 shadow-xl p-3 flex mx-auto">
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
import ApplicationLogo from "@/Components/ApplicationLogo.vue";


export default {
    name: "Home",
    components: {ApplicationLogo, Header, Link, Footer},
    props:{
      events: Object,
    },
    data() {
        return {
            flipped: false,
        };
    },
    methods: {
        flip() {
            this.flipped = !this.flipped;
        },
    },
}
</script>

<style scoped>
    .card {
        width: 75%;
        height: 150px;
        position: relative;
        perspective: 1000px;
    }

    .front,
    .back {
        width: 100%;
        height: 100%;
        position: absolute;
        backface-visibility: hidden;
        transition: transform 0.8s ease-in-out;
    }

    .front {
        transform: rotateY(0deg);
    }

    .back {
        transform: rotateY(180deg);
    }

    .card:hover .front {
        transform: rotateY(-180deg);
    }

    .card:hover .back {
        transform: rotateY(0deg);
    }

    .background{
        background-color: #4d7c0f;
        background-image: url("data:image/svg+xml,%3Csvg width='42' height='44' viewBox='0 0 42 44' xmlns='http://www.w3.org/2000/svg'%3E%3Cg id='Page-1' fill='none' fill-rule='evenodd'%3E%3Cg id='brick-wall' fill='%236ca81c' fill-opacity='0.23'%3E%3Cpath d='M0 0h42v44H0V0zm1 1h40v20H1V1zM0 23h20v20H0V23zm22 0h20v20H22V23z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    @media (max-width: 600px) {
        .card{
            width: 100%;
            height: 250px;
        }
    }
</style>
