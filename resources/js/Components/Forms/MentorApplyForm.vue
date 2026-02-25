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
                        placeholder="Izvēlieties programu"
                        required
                    />
                    <FormSelect
                        :icon="Calendar"
                        label="Studiju gads"
                        v-model="form.year"
                        :error="errors?.year"
                        :options="yearOptions"
                        required
                    />
                    </template>

                    <!-- Step 3: About -->
                    <template v-else-if="currentStep === 3">
                    <FormTextarea
                        :icon="FileText"
                        label="Par Tevi"
                        v-model="form.about"
                        :error="errors?.about"
                        :rows="4"
                        placeholder="Pastāstiet par sevi..."
                        required
                    />
                    <FormTextarea
                        :icon="MessageSquare"
                        label="Kāpēc gribi būt mentors?"
                        v-model="form.why"
                        :error="errors?.why"
                        :rows="4"
                        placeholder="Share your motivation..."
                        required
                    />
                    </template>

                    <!-- Step 4: Details -->
                    <template v-else-if="currentStep === 4">
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block flex items-center gap-2">
                            <Languages class="w-4 h-4 text-gray-400" />
                            Brīvi runā <span class="text-red-500">*</span>
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <FormCheckbox
                                v-model="form.lv"
                                label="Latviešu"
                                :error="errors?.lv"
                            />
                            <FormCheckbox
                                v-model="form.en"
                                label="Angļu"
                                :error="errors?.en"
                            />
                            <FormCheckbox
                                v-model="form.ru"
                                label="Krievu"
                                :error="errors?.ru"
                            />
                        </div>
                    </div>
                    <FormInput
                        :icon="Users"
                        label="Mentorējamo skaits"
                        v-model.number="form.mentees"
                        :error="errors?.mentees"
                        type="number"
                        :min="1"
                        :max="5"
                        @input="checkMenteesInput"
                        required
                    />
                    <FormFileUpload
                        v-model="form.img"
                        label="Pievienot attēlu"
                        :error="errors?.img"
                        required
                    />
                    </template>

                    <!-- Step 5: Confirm -->
                    <template v-else-if="currentStep === 5">
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
                v-if="currentStep < 5"
                type="button"
                class="w-full px-6 py-2.5 bg-accent-500 hover:bg-accent-600 text-white rounded-lg font-medium transition-colors"
                @click="nextStep"
            >
                Turpināt
            </button>
            <button
                v-if="currentStep === 5"
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
    Calendar,
    Users,
    FileText,
    MessageSquare,
    Languages,
} from 'lucide-vue-next';
import FormInput from './FormInput.vue';
import FormSelect from './FormSelect.vue';
import FormTextarea from './FormTextarea.vue';
import FormCheckbox from './FormCheckbox.vue';
import FormFileUpload from './FormFileUpload.vue';
import StepProgress from './StepProgress.vue';

export default {
    name: 'MentorApplyForm',
    components: {
        FormInput,
        FormSelect,
        FormTextarea,
        FormCheckbox,
        FormFileUpload,
        StepProgress,
        Languages,
    },
    props: {
        faculties: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            currentStep: 1,
            transitionDir: 'next',
            steps: [
                { id: 1, label: 'Personīgie dati' },
                { id: 2, label: 'Studijas' },
                { id: 3, label: 'Par Tevi' },
                { id: 4, label: 'Papildinformācija' },
                { id: 5, label: 'Apstiprinājums' },
            ],
            yearOptions: [
                { value: '2', label: '2. gads' },
                { value: '3', label: '3. gads' },
                { value: '4', label: '4. gads' },
                { value: '5', label: '5. gads' },
                { value: '6', label: '6. gads' },
            ],
            form: useForm({
                name: '',
                lastName: '',
                phone: '',
                email: '',
                faculty_id: 'default',
                program_id: 'default',
                year: '2',
                mentees: 5,
                about: '',
                why: '',
                lv: 0,
                ru: 0,
                en: 0,
                privacy: null,
                img: null,
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
        consentLabel() {
            return 'Piekrītu savu datu apstrādāšanai saskaņā ar datu izmantošanas politiku un esmu iepazinies ar <a class="underline hover:text-accent-600" href="/files/studentiem-mentoru_nolikums.docx">nolikumu</a>';
        },
    },
    methods: {
        Users,
        MessageSquare,
        FileText,
        Calendar,
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
                    return this.form.faculty_id !== 'default' &&
                        this.form.program_id !== 'default' &&
                        this.form.year;
                case 3:
                    return this.form.about?.trim() && this.form.why?.trim();
                case 4:
                    return (this.form.lv || this.form.en || this.form.ru) &&
                        this.form.img &&
                        this.form.mentees >= 1 &&
                        this.form.mentees <= 5;
                case 5:
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
        submit() {
            if (!this.form.privacy) {
                this.form.setError('privacy', 'Lūdzu, piekrītiet datu apstrādāšanai');
                return;
            }
            this.form.post(route('mentor.store'), {
                preserveState: 'errors',
                forceFormData: true,
            });
        },
        checkMenteesInput() {
            const val = this.form.mentees;
            if (val !== '' && (val > 5 || val < 1)) {
                this.form.mentees = 5;
            }
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
