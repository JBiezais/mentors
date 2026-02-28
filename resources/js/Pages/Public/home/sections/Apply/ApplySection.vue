<template>
    <div>
        <h3 class="text-2xl md:text-3xl lg:text-4xl font-medium text-gray-900 tracking-tight text-center">{{ $t('apply.title') }}</h3>
        <div
            role="tablist"
            class="flex mt-6 mb-8 justify-center"
        >
            <div class="inline-flex rounded-lg bg-gray-100 p-1 gap-1">
                <button
                    role="tab"
                    :aria-selected="activeTab === 'mentor'"
                    :class="[
                        'px-5 py-2.5 rounded-md font-medium text-base transition-colors duration-200',
                        activeTab === 'mentor'
                            ? 'bg-accent-500 text-white'
                            : 'text-gray-600 hover:text-accent-600 hover:bg-accent-50/50'
                    ]"
                    @click="setTab('mentor')"
                >
                    {{ $t('apply.tabMentor') }}
                </button>
                <button
                    role="tab"
                    :aria-selected="activeTab === 'mentee'"
                    :class="[
                        'px-5 py-2.5 rounded-md font-medium text-base transition-colors duration-200',
                        activeTab === 'mentee'
                            ? 'bg-accent-500 text-white'
                            : 'text-gray-600 hover:text-accent-600 hover:bg-accent-50/50'
                    ]"
                    @click="setTab('mentee')"
                >
                    {{ $t('apply.tabMentee') }}
                </button>
            </div>
        </div>
        <div class="overflow-hidden min-h-[400px]">
            <Transition :name="'slide-' + transitionDir" mode="out-in">
                <div :key="activeTab" class="w-full">
                    <MentorApplyForm
                        v-if="activeTab === 'mentor' && faculties"
                        :faculties="faculties"
                        @success="$emit('success', $event)"
                    />
                    <MentorRequestForm
                        v-else-if="activeTab === 'mentee' && faculties && mentors"
                        :faculties="faculties"
                        :mentors="mentors"
                        @success="$emit('success', $event)"
                    />
                </div>
            </Transition>
        </div>
    </div>
</template>

<script>
import MentorRequestForm from "@/Components/Forms/MentorRequestForm.vue";
import MentorApplyForm from "@/Components/Forms/MentorApplyForm.vue";

export default {
    name: "ApplySection",
    components: { MentorRequestForm, MentorApplyForm },
    props: {
        faculties: Array,
        mentors: Array
    },
    data() {
        return {
            activeTab: 'mentor',
            transitionDir: 'right'
        };
    },
    methods: {
        setTab(tab) {
            if (tab === this.activeTab) return;
            this.transitionDir = tab === 'mentee' ? 'right' : 'left';
            this.activeTab = tab;
        }
    }
};
</script>

<style scoped>
/* slide-right: Mentor leaves left, Mentee enters from right */
.slide-right-enter-active,
.slide-right-leave-active {
    transition: all 0.3s ease;
}

.slide-right-enter-from {
    transform: translateX(25%);
    opacity: 0;
}

.slide-right-enter-to {
    transform: translateX(0);
    opacity: 1;
}

.slide-right-leave-from {
    transform: translateX(0);
    opacity: 1;
}

.slide-right-leave-to {
    transform: translateX(-25%);
    opacity: 0;
}

/* slide-left: Mentee leaves right, Mentor enters from left */
.slide-left-enter-active,
.slide-left-leave-active {
    transition: all 0.3s ease;
}

.slide-left-enter-from {
    transform: translateX(-25%);
    opacity: 0;
}

.slide-left-enter-to {
    transform: translateX(0);
    opacity: 1;
}

.slide-left-leave-from {
    transform: translateX(0);
    opacity: 1;
}

.slide-left-leave-to {
    transform: translateX(25%);
    opacity: 0;
}
</style>
