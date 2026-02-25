<template>
    <form class="space-y-6 text-gray-800" @submit.prevent="submit()">
        <StepProgress :steps="steps" :currentStep="currentStep" />

        <div class="min-h-[320px]">
            <Transition :name="transitionName" mode="out-in">
                <div :key="currentStep" class="space-y-4">
                    <!-- Step 1: Personal -->
                    <template v-if="currentStep === 1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <FormInput
                                :icon="User"
                                label="Vārds"
                                v-model="form.name"
                                :error="errors?.name"
                                required
                            />
                            <FormInput
                                :icon="User"
                                label="Uzvārds"
                                v-model="form.lastName"
                                :error="errors?.lastName"
                                required
                            />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <FormInput
                                :icon="Phone"
                                label="Telefona numurs"
                                v-model="form.phone"
                                :error="errors?.phone"
                                type="tel"
                                required
                            />
                            <FormInput
                                :icon="Mail"
                                label="E-pasts"
                                v-model="form.email"
                                :error="errors?.email"
                                type="email"
                                required
                            />
                        </div>
                    </template>

                    <!-- Step 2: Studies -->
                    <template v-else-if="currentStep === 2">
                        <FormSelect
                            :icon="GraduationCap"
                            label="Fakultāte"
                            v-model="form.faculty_id"
                            :error="errors?.faculty_id"
                            :options="facultyOptions"
                            placeholder="Izvēlieties fakultāti"
                            required
                        />
                        <FormSelect
                            :icon="BookOpen"
                            label="Studiju programma"
                            v-model="form.program_id"
                            :error="errors?.program_id"
                            :options="programOptions"
                            placeholder="Izvēlieties studiju programmu"
                            required
                        />
                    </template>

                    <!-- Step 3: About & Language -->
                    <template v-else-if="currentStep === 3">
                        <FormTextarea
                            :icon="MessageSquare"
                            label="Komentāri"
                            v-model="form.comment"
                            :error="errors?.comment"
                            :rows="3"
                            placeholder="Papildu komentāri (pēc vēlēšanās)..."
                        />
                        <FormSelect
                            :icon="Languages"
                            label="Kurā valodā vēlies runāt ar mentoru?"
                            v-model="form.lang"
                            :error="errors?.lang"
                            :options="langOptions"
                            placeholder="Izvēlieties valodu"
                            required
                        />
                    </template>

                    <!-- Step 4: Mentor selection & Submit -->
                    <template v-else-if="currentStep === 4">
                        <div class="text-center md:text-left text-xl font-semibold" v-if="canShowMentors">
                            Izvēlies mentoru:
                            <div class="md:grid md:grid-cols-2 md:gap-5 mt-5">
                                <div class="col-span-2">
                                    <label class="flex text-base space-x-5 items-center cursor-pointer" @click="form.mentor_id = ''">
                                        <input type="checkbox" class="rounded border-gray-300 text-accent-500 focus:ring-accent-500/20 w-4 h-4" :checked="!form.mentor_id" readonly tabindex="-1">
                                        <span class="text-sm font-medium text-gray-700">Jebkurš mentors</span>
                                    </label>
                                </div>
                                <div class="col-span-2" v-if="displayMentors.length === 0">
                                    <p class="text-base">
                                        Diemžēl Tavā studiju programmā <strong>neviens Mentors šobrīd nav pieejams</strong>, taču nebēdā! Mēģināsim Tev piešķirt Mentoru no citas studiju programmas, kurš tāpat spēs pastāstīt par studiju procesu uzsākot mācības RSU!<br/>
                                    </p>
                                </div>
                                <div
                                    v-for="mentor in displayMentors"
                                    :key="mentor.id"
                                    class="rounded-xl p-2 space-y-3 cursor-pointer"
                                    :class="form.mentor_id == mentor.id ? 'border-2 border-accent-500 shadow-xl' : 'border border-gray-200 hover:border-accent-200'"
                                    @click="addMentor(mentor.id)"
                                >
                                    <img class="rounded-lg m-auto w-full h-auto" :src="'/'+mentor.img" alt="mentor">
                                    <div class="text-center text-lg">
                                        <h1>{{ mentor.name }} {{ mentor.lastName }}</h1>
                                        <p class="text-gray-500 italic text-sm">{{ mentor.year }}. gads</p>
                                    </div>
                                    <div class="text-center font-semibold text-base md:text-left">
                                        Par sevi:
                                        <p v-if="showFullAbout !== mentor.id" @click.stop="showFullAbout = mentor.id" class="font-medium">
                                            {{ mentor.about?.length > 200 ? mentor.about.substring(0, 200) + '...' : mentor.about }}
                                        </p>
                                        <p v-else @click.stop="showFullAbout = null" class="font-medium">{{ mentor.about }}</p>
                                    </div>
                                    <div class="text-center font-semibold text-base md:text-left">
                                        Kāpēc pieteicos mentorēt:
                                        <p v-if="showFullWhy !== mentor.id" @click.stop="showFullWhy = mentor.id" class="font-medium">
                                            {{ mentor.why?.length > 200 ? mentor.why.substring(0, 200) + '...' : mentor.why }}
                                        </p>
                                        <p v-else @click.stop="showFullWhy = null" class="font-medium">{{ mentor.why }}</p>
                                    </div>
                                    <div class="flex text-center font-semibold text-base">
                                        Valodas:
                                        <h1 class="ml-2 space-x-3 font-medium">
                                            <span v-if="mentor.lv">Latviešu</span>
                                            <span v-if="mentor.ru">Krievu</span>
                                            <span v-if="mentor.en">Angļu</span>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="errors?.mentor" />
                        </div>
                        <FormCheckbox
                            v-model="form.privacy"
                            :label="consentLabel"
                            :error="errors?.privacy"
                            required
                        />
                    </template>
                </div>
            </Transition>
        </div>

        <div class="flex flex-col gap-3 pt-4">
            <button
                v-if="currentStep < 4"
                type="button"
                class="w-full px-6 py-2.5 bg-accent-500 hover:bg-accent-600 text-white rounded-lg font-medium transition-colors"
                @click="nextStep"
            >
                Turpināt
            </button>
            <button
                v-if="currentStep === 4"
                type="submit"
                class="w-full px-6 py-2.5 bg-accent-500 hover:bg-accent-600 text-white rounded-lg font-medium transition-colors"
            >
                Nosūtīt pieteikumu
            </button>
            <button
                v-if="currentStep > 1"
                type="button"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors"
                @click="prevStep"
            >
                Atpakaļ
            </button>
        </div>
    </form>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import {
    User,
    Mail,
    Phone,
    GraduationCap,
    BookOpen,
    MessageSquare,
    Languages,
} from 'lucide-vue-next';
import FormInput from './FormInput.vue';
import FormSelect from './FormSelect.vue';
import FormTextarea from './FormTextarea.vue';
import FormCheckbox from './FormCheckbox.vue';
import StepProgress from './StepProgress.vue';
import InputError from '@/Components/InputError.vue';

export default {
    name: 'MentorRequestForm',
    components: {
        FormInput,
        FormSelect,
        FormTextarea,
        FormCheckbox,
        StepProgress,
        InputError,
    },
    props: {
        faculties: {
            type: Array,
            default: () => [],
        },
        mentors: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            currentStep: 1,
            transitionDir: 'next',
            showFullAbout: null,
            showFullWhy: null,
            steps: [
                { id: 1, label: 'Personīgie dati' },
                { id: 2, label: 'Studijas' },
                { id: 3, label: 'Par sevi' },
                { id: 4, label: 'Mentors un nosūtīt' },
            ],
            langOptions: [
                { value: 0, label: 'Latviešu' },
                { value: 1, label: 'Krievu' },
                { value: 2, label: 'Angļu' },
            ],
            form: useForm({
                name: '',
                lastName: '',
                phone: '',
                email: '',
                faculty_id: 'default',
                program_id: 'default',
                comment: '',
                lang: null,
                mentor_id: '',
                privacy: false,
            }),
        };
    },
    computed: {
        errors() {
            return this.$page?.props?.errors || {};
        },
        transitionName() {
            return 'slide-' + this.transitionDir;
        },
        facultyOptions() {
            return (this.faculties || []).map((f) => ({ value: f.id, label: f.title }));
        },
        programOptions() {
            const faculty = this.faculties?.find((f) => f.id == this.form.faculty_id);
            const programs = faculty?.programs || [];
            return programs.map((p) => ({
                value: p.id,
                label: `${p.title} (${p.level || ''})`,
            }));
        },
        canShowMentors() {
            return this.form.faculty_id !== 'default' &&
                this.form.program_id !== 'default' &&
                this.form.lang !== null &&
                this.form.lang !== undefined;
        },
        displayMentors() {
            if (!this.canShowMentors) return [];
            const lang = this.form.lang;
            return (this.mentors || []).filter((mentor) => {
                if (mentor.program_id != this.form.program_id) return false;
                const hasCapacity = mentor.mentees > (mentor.students_count || 0);
                if (!hasCapacity) return false;
                if (lang == 0 && mentor.lv) return true;
                if (lang == 1 && mentor.ru) return true;
                if (lang == 2 && mentor.en) return true;
                return false;
            });
        },
        consentLabel() {
            return 'Piekrītu savu datu apstrādāšanai saskaņā ar datu izmantošanas politiku';
        },
    },
    methods: {
        Languages,
        MessageSquare,
        BookOpen,
        GraduationCap,
        Mail,
        Phone,
        User,
        validateStep(step) {
            switch (step) {
                case 1:
                    return this.form.name?.trim() &&
                        this.form.lastName?.trim() &&
                        this.form.phone?.trim() &&
                        /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email);
                case 2:
                    return this.form.faculty_id !== 'default' && this.form.program_id !== 'default';
                case 3:
                    return this.form.lang !== null && this.form.lang !== undefined;
                case 4:
                    return !!this.form.privacy;
                default:
                    return true;
            }
        },
        nextStep() {
            if (!this.validateStep(this.currentStep)) return;
            this.transitionDir = 'next';
            this.currentStep++;
        },
        prevStep() {
            this.transitionDir = 'prev';
            this.currentStep--;
        },
        addMentor(id) {
            this.form.mentor_id = this.form.mentor_id == id ? '' : id;
        },
        submit() {
            if (!this.form.privacy) {
                this.form.setError('privacy', 'Lūdzu, piekrītiet datu apstrādāšanai');
                return;
            }
            this.form.post(route('student.store'), {
                preserveState: 'errors',
            });
        },
    },
    watch: {
        'form.faculty_id'() {
            this.form.program_id = 'default';
        },
    },
};
</script>

<style scoped>
.slide-next-enter-active,
.slide-next-leave-active,
.slide-prev-enter-active,
.slide-prev-leave-active {
    transition: all 0.25s ease;
}

.slide-next-enter-from {
    transform: translateX(20px);
    opacity: 0;
}

.slide-next-leave-to {
    transform: translateX(-20px);
    opacity: 0;
}

.slide-prev-enter-from {
    transform: translateX(-20px);
    opacity: 0;
}

.slide-prev-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
