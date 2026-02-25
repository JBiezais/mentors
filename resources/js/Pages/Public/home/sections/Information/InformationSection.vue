<template>
        <h3 class="text-2xl md:text-3xl lg:text-4xl font-medium text-gray-900 tracking-tight mb-12 md:mb-16 text-center">Uzzini vairāk</h3>
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            <nav class="flex flex-row lg:flex-col lg:w-64 shrink-0 gap-1 overflow-x-auto lg:overflow-visible">
                <button
                    v-for="(item, index) in tabs"
                    :key="index"
                    @click="selectedTab = index"
                    class="px-4 py-3 text-left whitespace-nowrap rounded-none transition-colors border-l-4"
                    :class="selectedTab === index ? 'bg-accent-50 border-accent-500 text-accent-700 font-medium' : 'border-transparent hover:bg-gray-50 text-gray-700'"
                >
                    {{ item.title }}
                </button>
            </nav>
            <div class="hidden lg:block w-px bg-gray-200 flex-shrink-0 self-stretch" aria-hidden="true"></div>
            <div class="flex-1 min-w-0">
                <div v-show="selectedTab === 0" class="space-y-4">
                    <h4 class="text-lg md:text-xl font-medium text-gray-900">Mentoru programma</h4>
                    <p class="text-gray-600 leading-relaxed max-w-prose">
                        Mentoru programma ir vieta, kur pieredzējuši studējošie savas zināšanas un pieredzi nodod pirmā studiju gada studējošajiem. RSU Mentoru programma latviešu studējošajiem tiek realizēta katru gadu no septembra līdz februārim, tā ir balstīta uz brīvprātības principa. Tās mērķis ir veicināt jauno studējošo integrāciju studiju vidē un sabiedriskajā dzīvē.
                    </p>
                </div>
                <div v-show="selectedTab === 1" class="space-y-4">
                    <h4 class="text-lg md:text-xl font-medium text-gray-900">Kas ir Mentors?</h4>
                    <p class="text-gray-600 leading-relaxed max-w-prose">
                        Mentors ir padomdevējs un palīgs pirmā studiju gada studējošajam. Tas ir studējošais, kurš uzsācis studijas vismaz otrajā studiju gadā un ir gatavs dalīties savās zināšanās un praktiskajā pieredzē ar jauno studējošo. Mentors var gan sniegt padomus, gan atbildēt uz jautājumiem. Vienam mentoram var būt līdz 5 mentorējamajiem.
                    </p>
                </div>
                <div v-show="selectedTab === 2" class="space-y-4">
                    <h4 class="text-lg md:text-xl font-medium text-gray-900">Kas ir Mentorējamais?</h4>
                    <p class="text-gray-600 leading-relaxed max-w-prose">
                        Par mentorējamo var kļūt jebkurš pirmā studiju gada studējošais. Mentorējamais ir studējošais, kurš vēlas iegūt zināšanas par studiju procesa norisi, par sabiedriskās dzīves iespējām universitātē, vēlas iegūt jaunus kontaktus un pieredzi.
                    </p>
                </div>
            </div>
        </div>
</template>

<script>
export default {
    name: "InformationSection",
    data() {
        return {
            selectedTab: 0,
            tabs: [
                { title: 'Mentoru programma' },
                { title: 'Kas ir Mentors?' },
                { title: 'Kas ir Mentorējamais?' }
            ]
        };
    }
};
</script>
