<template>
    <div class="min-h-screen flex flex-col bg-gray-100">
        <Header v-if="$page.props.auth.user !== null"></Header>
        <div v-if="$page.props.auth.user === null" class="w-full sticky top-0 z-10 bg-gray-100">
            <div class="lg:max-w-5xl m-auto flex">
                <ApplicationLogo
                    class="block h-16 w-auto my-auto fill-current text-gray-800"
                />
                <div class="m-auto">
                    <h1 class="text-xl md:text-2xl font-semibold uppercase text-center">mentoru programma {{new Date().getFullYear()}}</h1>
                </div>
            </div>
        </div>
        <Modal :show="message" @close="message = null">
            <div class="p-5 flex flex-col">
                <div class="space-y-2">
                    <h1 class="font-semibold text-xl">{{message.title}}</h1>
                    <hr>
                </div>
                <h1 class="my-5">{{message.text}}</h1>
                <hr>
                <PrimaryButton class="ml-auto mt-2" @click="message = null">Aizvērt</PrimaryButton>
            </div>
        </Modal>
        <div class="w-full">
            <img src="/img/banner.png" class="w-full">
        </div>
        <div class="flex-grow" style="background-image: url('/img/bg.png'); background-size: cover; background-repeat: no-repeat">
        <div class="flex-grow">
            <div class="w-full py-8 px-3">
                <div class="lg:max-w-5xl mx-auto flex flex-col md:flex-row space-y-5 md:space-x-8 md:space-y-0">
                    <div class="flex flex flex-col md:flex-row space-y-5 md:space-x-8 md:space-y-0 w-full">
                        <div class="rounded-xl bg-gray-50 p-5 h-fit my-auto" :class="events.length? 'md:w-1/2': 'w-full'">
                            <div class="flex flex-col space-y-8 md:justify-center" :class="events.length? 'space-y-8': 'md:flex-row md:space-x-4 md:space-y-0'">
                                <Link :href="route('student.create')" class="flex py-5 uppercase px-8 bg-blue-750 text-white shadow-xl rounded-xl mx-auto md:mx-0 cursor-pointer w-full hover:scale-105 transition-all duration-100">
                                    <h1 class="text-xl text-center font-bold m-auto">Pieteikties mentoram</h1>
                                </Link>
                                <Link :href="route('mentor.create')" class="flex py-5 uppercase px-8 bg-blue-750 text-white shadow-xl rounded-xl mx-auto md:mx-0 cursor-pointer w-full hover:scale-105 transition-all duration-100">
                                    <h1 class="text-xl text-center my-auto font-bold m-auto" >Kļūt par mentoru</h1>
                                </Link>
                                <div class="py-5 px-8 uppercase bg-blue-750 text-white shadow-xl rounded-xl mx-auto align-middle md:mx-0 cursor-pointer text-xl font-bold my-auto flex w-full hover:scale-105 transition-all duration-100">
                                    <a class="text-center mx-auto" href="https://www.rsu.lv/form/arvalstu-studentu-mentora-pietei">Mentors ārvalstu studējošajiem</a>
                                </div>
                            </div>
                        </div>
                        <div class="md:w-1/2 rounded-xl bg-gray-50 p-5 h-fit" v-if="events.length">
                            <div class="space-y-4 md:justify-center">
                                <h1 class="text-2xl text-center text-blue-850 uppercase font-bold">Gaidāmie notikumi</h1>
                                <div class="space-y-4 overflow-y-auto h-96 pb-4">
                                    <div v-for="event in events" class="flex space-x-5 border border-gray-300 rounded-xl shadow-lg p-1">
                                        <div class="py-3 px-4 rounded-xl">
                                            <h1 class="text-3xl text-center font-black -my-1">{{new Date(event.date).getDate()}}</h1>
                                            <h1 class="text-xs text-center uppercase">{{new Date(event.date).toLocaleDateString("lv-LV", { month: "short" })}}</h1>
                                        </div>
                                        <div class="w-2/3 flex flex-col my-auto">
                                            <h1 class="font-bold">{{event.title}}</h1>
                                            <div class="flex space-x-4">
                                                <div class="flex space-x-2">
                                                    <img src="/img/clock.svg" alt="location" class="h-4 w-auto my-auto">
                                                    <h1 class="my-auto text-sm">{{new Date(event.date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}}</h1>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <img src="/img/location.svg" alt="location" class="h-4 w-auto my-auto">
                                                    <h1 class="my-auto text-sm break-all">{{event.location}}</h1>
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
            <div class="lg:max-w-5xl bg-gray-50 space-y-9 mb-5 mx-3 md:mx-auto rounded-xl padding">
                <div class="p-8">
                    <div class="lg:max-w-5xl space-y-5 mx-auto">
                        <div class="flex flex-col space-y-5">
                            <div class="relative">
                                <div class="card extra-height">
                                    <div class="md:w-3/4 bg-gray-200 shadow-xl rounded-xl flex front">
                                        <div class="w-3 rounded-l-xl bg-yellow-950 h-auto"></div>
                                        <div class="p-5 w-fit flex">
                                            <h1 class="text-2xl my-auto text-gray-700">Uzzini vairāk par <span class="text-blue-850 font-semibold">Mentoru Programmu!</span><br/>
                                                Mentoru programma ir vieta, kur pieredzējuši studējošie savas zināšanas un pieredzi nodod pirmā studiju gada studējošajiem.
                                            </h1>
                                        </div>
                                    </div>
                                    <div class="md:w-3/4 bg-gray-200 shadow-xl rounded-xl flex back">
                                        <div class="p-5 w-fit">
                                            <p class="text-xl italic ">RSU Mentoru programma latviešu studējošajiem tiek realizēta katru gadu no septembra līdz februārim, tā ir balstīta uz brīvprātības principa. Tās mērķis ir veicināt jauno studējošo integrāciju studiju vidē un sabiedriskajā dzīvē. Mentoru programmā jaunie studējošie jeb mentorējamie iegūst zināšanas no vecāko studiju gadu studējošajiem jeb mentoriem. </p>
                                        </div>
                                        <div class="flex-grow"></div>
                                        <div class="w-3 rounded-r-xl bg-blue-950 h-auto"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="relative flex">
                                <div class="flex-grow"></div>
                                <div class="card extra-height">
                                    <div class="md:w-3/4 bg-gray-200 shadow-xl rounded-xl flex front">
                                        <div class="p-5 w-fit flex">
                                            <h1 class="text-2xl my-auto text-gray-700">Uzzini, kas ir <span class="text-blue-850 font-semibold">MENTORS</span><br/>
                                                Mentors ir padomdevējs un palīgs pirmā studiju gada studējošajam.
                                            </h1>
                                        </div>
                                        <div class="flex-grow"></div>
                                        <div class="w-3 rounded-r-xl bg-yellow-950 h-auto"></div>
                                    </div>
                                    <div class="md:w-3/4 bg-gray-200 shadow-xl rounded-xl flex back">
                                        <div class="w-3 rounded-l-xl bg-blue-950 h-auto"></div>
                                        <div class="p-5 w-fit">
                                            <p class="text-xl italic ">Mentors ir studējošais, kurš uzsācis studijas otrajā studiju gadā un ir gatavs dalīties savās zināšanās un praktiskajā pieredzē ar jauno studējošo. Mentors var gan sniegt padomus, gan atbildēt uz mentorējamajam interesējošiem jautājumiem. Mentors tiek piešķirts uz pirmo mācību semestri un vienam mentoram var būt līdz 5 mentorējamajiem. </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="relative">
                                <div class="card default-height">
                                    <div class="md:w-3/4 bg-gray-200 shadow-xl rounded-xl flex front">
                                        <div class="w-3 rounded-l-xl bg-yellow-950 h-auto"></div>
                                        <div class="p-5 w-fit flex">
                                            <h1 class="text-2xl my-auto text-gray-700">Uzzini, kas ir <span class="text-blue-850 font-semibold">MENTORĒJAMAIS</span><br/>
                                                Par mentorējamo var kļūt jebkurš pirmā studiju gada studējošais.
                                            </h1>
                                        </div>
                                    </div>
                                    <div class="md:w-3/4 bg-gray-200 shadow-xl rounded-xl flex back">
                                        <div class="p-5 w-fit">
                                            <p class="text-xl italic ">Mentorējamais ir pirmā studiju gada studējošais, kurš vēlas iegūt zināšanas par studiju procesa norisi, par sabiedriskās dzīves iespējām universitātē, vēlas iegūt jaunus kontaktus un pieredzi.
                                            </p>
                                        </div>
                                        <div class="flex-grow"></div>
                                        <div class="w-3 rounded-r-xl bg-blue-950 h-auto"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-8">
                    <div class="lg:max-w-5xl space-y-5 mx-auto">
                        <div class="w-full space-y-3 md:flex md:space-y-0 md:space-x-5">
                            <div class="md:my-auto">
                                <div class="w-fit h-fit bg-yellow-950 rounded-full border-gray-400 shadow-xl p-3 flex mx-auto">
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
                                <div class="w-fit h-fit bg-yellow-950 rounded-full border-gray-400 shadow-xl p-3 flex mx-auto">
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
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";


export default {
    name: "Home",
    components: {PrimaryButton, Modal, ApplicationLogo, Header, Link, Footer},
    props:{
        events: Object,
        message: String
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
    .padding {
        padding: 1.25rem;
    }
    .card {
        width: 75%;
        position: relative;
        perspective: 1000px;
    }
    .default-height{
        height: 150px;
    }
    .extra-height{
        height: 200px;
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

    @media (max-width: 926px){
        .default-height{
            height: 250px;
        }
        .extra-height{
            height: 300px;
        }
    }
    @media (max-width: 665px) {
        .extra-height{
            height: 500px;
        }
    }
    @media (max-width: 500px) {
        .card{
            width: 100%;
        }
        .default-height{
            height: 300px;
        }
        .extra-height{
            height: 550px;
        }
    }
    @media (max-width: 375px) {
        .padding{
            padding: 1.25rem 0.25rem;
        }
        .card{
            width: 100%;
        }
        .default-height{
            height: 450px;
        }
        .extra-height{
            height: 800px;
        }
    }
</style>
