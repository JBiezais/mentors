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
                                :label="$t('mentorRequest.labels.name')"
                                v-model="form.name"
                                :error="errors?.name"
                                required
                            />
                            <FormInput
                                :icon="User"
                                :label="$t('mentorRequest.labels.lastName')"
                                v-model="form.lastName"
                                :error="errors?.lastName"
                                required
                            />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <FormInput
                                :icon="Phone"
                                :label="$t('mentorRequest.labels.phone')"
                                v-model="form.phone"
                                :error="errors?.phone"
                                type="tel"
                                required
                            />
                            <FormInput
                                :icon="Mail"
                                :label="$t('mentorRequest.labels.email')"
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
                            :label="$t('mentorRequest.labels.faculty')"
                            v-model="form.faculty_id"
                            :error="errors?.faculty_id"
                            :options="facultyOptions"
                            :placeholder="$t('mentorRequest.placeholders.faculty')"
                            required
                        />
                        <FormSelect
                            :icon="BookOpen"
                            :label="$t('mentorRequest.labels.program')"
                            v-model="form.program_id"
                            :error="errors?.program_id"
                            :options="programOptions"
                            :placeholder="$t('mentorRequest.placeholders.program')"
                            required
                        />
                    </template>

                    <!-- Step 3: About & Language -->
                    <template v-else-if="currentStep === 3">
                        <FormTextarea
                            :icon="MessageSquare"
                            :label="$t('mentorRequest.labels.comments')"
                            v-model="form.comment"
                            :error="errors?.comment"
                            :rows="3"
                            :placeholder="$t('mentorRequest.placeholders.comments')"
                        />
                        <FormSelect
                            :icon="Languages"
                            :label="$t('mentorRequest.labels.languageQuestion')"
                            v-model="form.lang"
                            :error="errors?.lang"
                            :options="langOptions"
                            :placeholder="$t('mentorRequest.placeholders.language')"
                            required
                        />
                    </template>

                    <!-- Step 4: Mentor selection & Submit -->
                    <template v-else-if="currentStep === 4">
                        <div class="text-center md:text-left text-xl font-semibold" v-if="canShowMentors">
                            {{ $t('mentorRequest.mentorSelection.chooseMentor') }}
                            <div class="md:grid md:grid-cols-2 md:gap-5 mt-5">
                                <div class="col-span-2">
                                    <label class="flex text-base space-x-5 items-center cursor-pointer" @click="form.mentor_id = ''">
                                        <input type="checkbox" class="rounded border-gray-300 text-accent-500 focus:ring-accent-500/20 w-4 h-4" :checked="!form.mentor_id" readonly tabindex="-1">
                                        <span class="text-sm font-medium text-gray-700">{{ $t('mentorRequest.mentorSelection.anyMentor') }}</span>
                                    </label>
                                </div>
                                <div class="col-span-2" v-if="displayMentors.length === 0">
                                    <p class="text-base">
                                        {{ $t('mentorRequest.mentorSelection.noMentorsAvailable') }}<br/>
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
                                        <p class="text-gray-500 italic text-sm">{{ $t('mentorRequest.yearDisplay', { year: mentor.year }) }}</p>
                                    </div>
                                    <div class="text-center font-semibold text-base md:text-left">
                                        {{ $t('mentorRequest.mentorSelection.aboutMe') }}
                                        <p v-if="showFullAbout !== mentor.id" @click.stop="showFullAbout = mentor.id" class="font-medium">
                                            {{ mentor.about?.length > 200 ? mentor.about.substring(0, 200) + '...' : mentor.about }}
                                        </p>
                                        <p v-else @click.stop="showFullAbout = null" class="font-medium">{{ mentor.about }}</p>
                                    </div>
                                    <div class="text-center font-semibold text-base md:text-left">
                                        {{ $t('mentorRequest.mentorSelection.whyMentor') }}
                                        <p v-if="showFullWhy !== mentor.id" @click.stop="showFullWhy = mentor.id" class="font-medium">
                                            {{ mentor.why?.length > 200 ? mentor.why.substring(0, 200) + '...' : mentor.why }}
                                        </p>
                                        <p v-else @click.stop="showFullWhy = null" class="font-medium">{{ mentor.why }}</p>
                                    </div>
                                    <div class="flex text-center font-semibold text-base">
                                        {{ $t('mentorRequest.mentorSelection.languages') }}
                                        <h1 class="ml-2 space-x-3 font-medium">
                                            <span v-if="mentor.lv">{{ $t('mentorRequest.languages.lv') }}</span>
                                            <span v-if="mentor.ru">{{ $t('mentorRequest.languages.ru') }}</span>
                                            <span v-if="mentor.en">{{ $t('mentorRequest.languages.en') }}</span>
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
                {{ $t('mentorRequest.buttons.continue') }}
            </button>
            <button
                v-if="currentStep === 4"
                type="submit"
                :disabled="submitting"
                class="w-full px-6 py-2.5 bg-accent-500 hover:bg-accent-600 text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ submitting ? '...' : $t('mentorRequest.buttons.submit') }}
            </button>
            <button
                v-if="currentStep > 1"
                type="button"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors"
                @click="prevStep"
            >
                {{ $t('mentorRequest.buttons.back') }}
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
    emits: ['success'],
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
            stepErrors: {},
            showFullAbout: null,
            showFullWhy: null,
            stepKeys: [
                { id: 1, labelKey: 'mentorRequest.steps.personal' },
                { id: 2, labelKey: 'mentorRequest.steps.studies' },
                { id: 3, labelKey: 'mentorRequest.steps.about' },
                { id: 4, labelKey: 'mentorRequest.steps.mentorSubmit' },
            ],
            form: {
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
            },
            apiErrors: {},
            submitting: false,
        };
    },
    computed: {
        steps() {
            return this.stepKeys.map((s) => ({ id: s.id, label: this.$t(s.labelKey) }));
        },
        langOptions() {
            return [
                { value: 0, label: this.$t('mentorRequest.languages.lv') },
                { value: 1, label: this.$t('mentorRequest.languages.ru') },
                { value: 2, label: this.$t('mentorRequest.languages.en') },
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
            return this.$t('mentorRequest.consent');
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
        getStepErrors(step) {
            const err = {};
            switch (step) {
                case 1:
                    if (!this.form.name?.trim()) err.name = this.$t('mentorRequest.errors.name');
                    if (!this.form.lastName?.trim()) err.lastName = this.$t('mentorRequest.errors.lastName');
                    if (!this.form.phone?.trim()) err.phone = this.$t('mentorRequest.errors.phone');
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) err.email = this.$t('mentorRequest.errors.email');
                    break;
                case 2:
                    if (this.form.faculty_id === 'default') err.faculty_id = this.$t('mentorRequest.errors.faculty');
                    if (this.form.program_id === 'default') err.program_id = this.$t('mentorRequest.errors.program');
                    break;
                case 3:
                    if (this.form.lang === null || this.form.lang === undefined) err.lang = this.$t('mentorRequest.errors.language');
                    break;
                case 4:
                    if (!this.form.privacy) err.privacy = this.$t('mentorRequest.errors.privacy');
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
        addMentor(id) {
            this.form.mentor_id = this.form.mentor_id == id ? '' : id;
        },
        async submit() {
            if (!this.form.privacy) {
                this.stepErrors = { privacy: this.$t('mentorRequest.errors.privacy') };
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
            formData.append('comment', this.form.comment ?? '');
            if (this.form.lang !== null && this.form.lang !== undefined) {
                formData.append('lang', this.form.lang);
            }
            if (this.form.mentor_id) {
                formData.append('mentor_id', this.form.mentor_id);
            }
            formData.append('privacy', this.form.privacy ? 1 : 0);

            try {
                const response = await window.axios.post(route('student.store'), formData, {
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
                    comment: '',
                    lang: null,
                    mentor_id: '',
                    privacy: false,
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
