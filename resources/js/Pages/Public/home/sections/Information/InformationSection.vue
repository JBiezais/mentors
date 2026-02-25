<template>
        <h3 class="text-2xl md:text-3xl lg:text-4xl font-medium text-gray-900 tracking-tight mb-12 md:mb-16 text-center">{{ $t('information.title') }}</h3>
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
            <nav class="flex flex-row lg:flex-col lg:w-64 shrink-0 gap-1 overflow-x-auto lg:overflow-visible">
                <button
                    v-for="(item, index) in tabKeys"
                    :key="index"
                    @click="selectedTab = index"
                    class="px-4 py-3 text-left whitespace-nowrap rounded-none transition-colors border-l-4"
                    :class="selectedTab === index ? 'bg-accent-50 border-accent-500 text-accent-700 font-medium' : 'border-transparent hover:bg-gray-50 text-gray-700'"
                >
                    {{ $t('information.tabs.' + item) }}
                </button>
            </nav>
            <div class="hidden lg:block w-px bg-gray-200 flex-shrink-0 self-stretch" aria-hidden="true"></div>
            <div class="flex-1 min-w-0">
                <div v-show="selectedTab === 0" class="space-y-4">
                    <h4 class="text-lg md:text-xl font-medium text-gray-900">{{ $t('information.programTitle') }}</h4>
                    <p class="text-gray-600 leading-relaxed max-w-prose">
                        {{ $t('information.programContent') }}
                    </p>
                </div>
                <div v-show="selectedTab === 1" class="space-y-4">
                    <h4 class="text-lg md:text-xl font-medium text-gray-900">{{ $t('information.mentorTitle') }}</h4>
                    <p class="text-gray-600 leading-relaxed max-w-prose">
                        {{ $t('information.mentorContent') }}
                    </p>
                </div>
                <div v-show="selectedTab === 2" class="space-y-4">
                    <h4 class="text-lg md:text-xl font-medium text-gray-900">{{ $t('information.menteeTitle') }}</h4>
                    <p class="text-gray-600 leading-relaxed max-w-prose">
                        {{ $t('information.menteeContent') }}
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
            tabKeys: ['program', 'mentor', 'mentee']
        };
    }
};
</script>
