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
                            :label="$t('mentorApply.labels.name')"
                            v-model="form.name"
                            :error="errors?.name"
                            required
                        />
                        <FormInput
                            :icon="User"
                            :label="$t('mentorApply.labels.lastName')"
                            v-model="form.lastName"
                            :error="errors?.lastName"
                            required
                        />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <FormInput
                            :icon="Phone"
                            :label="$t('mentorApply.labels.phone')"
                            v-model="form.phone"
                            :error="errors?.phone"
                            type="tel"
                            required
                        />
                        <FormInput
                            :icon="Mail"
                            :label="$t('mentorApply.labels.email')"
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
                        :label="$t('mentorApply.labels.faculty')"
                        v-model="form.faculty_id"
                        :error="errors?.faculty_id"
                        :options="facultyOptions"
                        :placeholder="$t('mentorApply.placeholders.faculty')"
                        required
                    />
                    <FormSelect
                        :icon="BookOpen"
                        :label="$t('mentorApply.labels.program')"
                        v-model="form.program_id"
                        :error="errors?.program_id"
                        :options="programOptions"
                        :placeholder="$t('mentorApply.placeholders.program')"
                        required
                    />
                    <FormSelect
                        :icon="Calendar"
                        :label="$t('mentorApply.labels.year')"
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
                        :label="$t('mentorApply.labels.about')"
                        v-model="form.about"
                        :error="errors?.about"
                        :rows="4"
                        :placeholder="$t('mentorApply.placeholders.about')"
                        required
                    />
                    <FormTextarea
                        :icon="MessageSquare"
                        :label="$t('mentorApply.labels.whyMentor')"
                        v-model="form.why"
                        :error="errors?.why"
                        :rows="4"
                        :placeholder="$t('mentorApply.placeholders.why')"
                        required
                    />
                    </template>

                    <!-- Step 4: Details -->
                    <template v-else-if="currentStep === 4">
                    <div>
                        <span class="text-sm font-medium text-gray-700 mb-2 block flex items-center gap-2">
                            <Languages class="w-4 h-4 text-gray-400" />
                            {{ $t('mentorApply.labels.languages') }} <span class="text-red-500">*</span>
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <FormCheckbox
                                v-model="form.lv"
                                :label="$t('mentorApply.languages.lv')"
                                :error="errors?.lv"
                            />
                            <FormCheckbox
                                v-model="form.en"
                                :label="$t('mentorApply.languages.en')"
                                :error="errors?.en"
                            />
                            <FormCheckbox
                                v-model="form.ru"
                                :label="$t('mentorApply.languages.ru')"
                                :error="errors?.ru"
                            />
                        </div>
                        <InputError class="mt-1" :message="errors?.languages" />
                    </div>
                    <FormInput
                        :icon="Users"
                        :label="$t('mentorApply.labels.menteesCount')"
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
                        :label="$t('mentorApply.labels.addImage')"
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
                {{ $t('mentorApply.buttons.continue') }}
            </button>
            <button
                v-if="currentStep === 5"
                type="submit"
                :disabled="submitting"
                class="w-full px-6 py-2.5 bg-accent-500 hover:bg-accent-600 text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ submitting ? '...' : $t('mentorApply.buttons.submit') }}
            </button>
            <button
                v-if="currentStep > 1"
                type="button"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors"
                @click="prevStep"
            >
                {{ $t('mentorApply.buttons.back') }}
            </button>
        </div>
    </form>
</template>

<script>
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
import InputError from '@/Components/InputError.vue';

export default {
    name: 'MentorApplyForm',
    components: {
        FormInput,
        FormSelect,
        FormTextarea,
        FormCheckbox,
        FormFileUpload,
        StepProgress,
        InputError,
        Languages,
    },
    emits: ['success'],
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
            stepErrors: {},
            stepKeys: [
                { id: 1, labelKey: 'mentorApply.steps.personal' },
                { id: 2, labelKey: 'mentorApply.steps.studies' },
                { id: 3, labelKey: 'mentorApply.steps.about' },
                { id: 4, labelKey: 'mentorApply.steps.details' },
                { id: 5, labelKey: 'mentorApply.steps.confirm' },
            ],
            form: {
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
                lv: false,
                ru: false,
                en: false,
                privacy: null,
                img: null,
            },
            apiErrors: {},
            submitting: false,
        };
    },
    computed: {
        steps() {
            return this.stepKeys.map((s) => ({ id: s.id, label: this.$t(s.labelKey) }));
        },
        yearOptions() {
            return [
                { value: '2', label: this.$t('mentorApply.yearOptions.2') },
                { value: '3', label: this.$t('mentorApply.yearOptions.3') },
                { value: '4', label: this.$t('mentorApply.yearOptions.4') },
                { value: '5', label: this.$t('mentorApply.yearOptions.5') },
                { value: '6', label: this.$t('mentorApply.yearOptions.6') },
            ];
        },
        errors() {
            return { ...this.apiErrors, ...this.stepErrors };
        },
        transitionName() {
            return 'slide-' + this.transitionDir;
        },
        facultyOptions() {
            return (this.faculties || []).map((f) => ({
                value: f.id,
                label: f.code && this.$te(`faculties.${f.code}`)
                    ? this.$t(`faculties.${f.code}`)
                    : f.title,
            }));
        },
        programOptions() {
            const faculty = this.faculties?.find((f) => f.id == this.form.faculty_id);
            const programs = faculty?.programs || [];
            return programs.map((p) => {
                const baseLabel = p.code && this.$te(`programs.${p.code}`)
                    ? this.$t(`programs.${p.code}`)
                    : p.title;
                return {
                    value: p.id,
                    label: `${baseLabel} (${p.level || ''})`,
                };
            });
        },
        consentLabel() {
            return this.$t('mentorApply.consent') + ' <a class="underline hover:text-accent-600" href="/files/studentiem-mentoru_nolikums.docx">' + this.$t('mentorApply.consentLink') + '</a>';
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
        getStepErrors(step) {
            const err = {};
            switch (step) {
                case 1:
                    if (!this.form.name?.trim()) err.name = this.$t('mentorApply.errors.name');
                    if (!this.form.lastName?.trim()) err.lastName = this.$t('mentorApply.errors.lastName');
                    if (!this.form.phone?.trim()) err.phone = this.$t('mentorApply.errors.phone');
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) err.email = this.$t('mentorApply.errors.email');
                    break;
                case 2:
                    if (this.form.faculty_id === 'default') err.faculty_id = this.$t('mentorApply.errors.faculty');
                    if (this.form.program_id === 'default') err.program_id = this.$t('mentorApply.errors.program');
                    if (!this.form.year) err.year = this.$t('mentorApply.errors.year');
                    break;
                case 3:
                    if (!this.form.about?.trim()) err.about = this.$t('mentorApply.errors.about');
                    if (!this.form.why?.trim()) err.why = this.$t('mentorApply.errors.why');
                    break;
                case 4:
                    if (!(this.form.lv || this.form.en || this.form.ru)) err.languages = this.$t('mentorApply.errors.languages');
                    if (this.form.mentees < 1 || this.form.mentees > 5) err.mentees = this.$t('mentorApply.errors.mentees');
                    if (!this.form.img) err.img = this.$t('mentorApply.errors.img');
                    break;
                case 5:
                    if (!this.form.privacy) err.privacy = this.$t('mentorApply.errors.privacy');
                    break;
            }
            return err;
        },
        nextStep() {
            if (!this.validateStep(this.currentStep)) {
                this.stepErrors = this.getStepErrors(this.currentStep);
                return;
            }
            this.stepErrors = {};
            this.transitionDir = 'next';
            this.currentStep++;
        },
        prevStep() {
            this.stepErrors = {};
            this.transitionDir = 'prev';
            this.currentStep--;
        },
        async submit() {
            if (!this.form.privacy) {
                this.stepErrors = { privacy: this.$t('mentorApply.errors.privacy') };
                return;
            }
            this.apiErrors = {};
            this.stepErrors = {};
            this.submitting = true;

            const formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('lastName', this.form.lastName);
            formData.append('phone', this.form.phone);
            formData.append('email', this.form.email);
            formData.append('faculty_id', this.form.faculty_id);
            formData.append('program_id', this.form.program_id);
            formData.append('year', this.form.year);
            formData.append('mentees', this.form.mentees);
            formData.append('about', this.form.about);
            formData.append('why', this.form.why);
            formData.append('lv', this.form.lv ? 1 : 0);
            formData.append('ru', this.form.ru ? 1 : 0);
            formData.append('en', this.form.en ? 1 : 0);
            formData.append('privacy', this.form.privacy ? 1 : 0);
            if (this.form.img) {
                formData.append('img', this.form.img);
            }

            try {
                const response = await window.axios.post(route('mentor.store'), formData, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.$page.props.csrf_token,
                    },
                });
                this.$emit('success', response.data.message);
                this.form = {
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
                    lv: false,
                    ru: false,
                    en: false,
                    privacy: null,
                    img: null,
                };
                this.currentStep = 1;
            } catch (err) {
                if (err.response?.status === 422 && err.response?.data?.errors) {
                    const e = err.response.data.errors;
                    this.apiErrors = Object.fromEntries(
                        Object.entries(e).map(([k, v]) => [k, Array.isArray(v) ? v[0] : v])
                    );
                }
            } finally {
                this.submitting = false;
            }
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
