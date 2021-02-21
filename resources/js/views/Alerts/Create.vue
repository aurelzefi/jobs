<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Create New Alert') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <form-section @submitted="store">
                    <template #title>
                        {{ __('Title') }}
                    </template>

                    <template #description>
                        {{ __('Description') }}
                    </template>

                    <template #form>
                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="name">{{ __('Name') }}</app-label>
                            <app-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" ref="name" />
                            <app-input-error :message="form.errors.name" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="type">{{ __('Type') }}</app-label>
                            <app-select id="type" class="mt-1 block w-full" :options="alertTypes" :default-option="__('Select a type')" v-model="form.type" />
                            <app-input-error :message="form.errors.type" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="city">{{ __('City') }}</app-label>
                            <app-input id="city" type="text" class="mt-1 block w-full" v-model="form.city" />
                            <app-input-error :message="form.errors.city" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="country">{{ __('Country') }}</app-label>
                            <app-select id="country" class="mt-1 block w-full" :options="countries" :default-option="__('Select a country')" v-model="form.country_id" />
                            <app-input-error :message="form.errors.country_id" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="keywords">{{ __('Keywords') }}</app-label>
                            <app-input id="keywords" type="text" class="mt-1 block w-full" v-model="form.keywords" />
                            <secondary-text>{{ __('A comma separated list of keywords.') }}</secondary-text>
                            <app-input-error :message="form.errors.keywords" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="has-all-keywords">
                                <div class="flex items-center">
                                    <app-checkbox id="has-all-keywords" v-model="form.has_all_keywords" />

                                    <div class="ml-2">
                                        {{ __('Should Contain All Keywords') }}
                                    </div>
                                </div>
                            </app-label>
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="job-types">
                                {{ __('Job Types') }}
                            </app-label>

                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-for="type in App.jobTypes" :key="type">
                                    <label class="flex items-center">
                                        <app-checkbox :value="type" v-model="form.job_types"/>
                                        <span class="ml-2 text-sm text-gray-600">{{ __(type) }}</span>
                                    </label>
                                </div>
                            </div>

                            <app-input-error :message="form.errors.job_types" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="job-types">
                                {{ __('Job Styles') }}
                            </app-label>

                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div v-for="style in App.jobStyles" :key="style">
                                    <label class="flex items-center">
                                        <app-checkbox :value="style" v-model="form.job_styles"/>
                                        <span class="ml-2 text-sm text-gray-600">{{ __(style) }}</span>
                                    </label>
                                </div>
                            </div>

                            <app-input-error :message="form.errors.job_styles" class="mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <app-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ __('Save') }}
                        </app-button>
                    </template>
                </form-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ActionMessage from '../../components/ActionMessage'
import AppButton from '../../components/Button'
import AppCheckbox from '../../components/Checkbox'
import FormSection from '../../components/FormSection'
import AppInput from '../../components/Input'
import AppInputError from '../../components/InputError'
import AppLabel from '../../components/Label'
import SecondaryText from '../../components/SecondaryText'
import SectionBorder from '../../components/SectionBorder'
import AppSelect from '../../components/Select'
import AppLayout from '../../layouts/AppLayout'

export default {
    components: {
        ActionMessage,
        AppButton,
        AppCheckbox,
        FormSection,
        AppInput,
        AppInputError,
        AppLabel,
        SecondaryText,
        SectionBorder,
        AppSelect,
        AppLayout
    },

    data() {
        return {
            form: this.$form.create({
                country_id: '',
                name: '',
                has_all_keywords: false,
                city: '',
                type: '',
                job_types: [],
                job_styles: [],
                keywords: ''
            }),

            alertTypes: {},

            countries: {}
        }
    },

    beforeRouteEnter(to, from, next) {
        axios.get('/api/countries')
            .then(response => {
                next(vm => {
                    vm.countries = vm.lodash.mapValues(response.data, country => country.name)
                })
            })
    },

    mounted() {
        this.alertTypes = this.keyByValues(this.App.alertTypes)

        this.form.job_types = this.App.jobTypes
        this.form.job_styles = this.App.jobStyles

        this.$refs.name.focus()
    },

    methods: {
        store() {
            this.form.post('/api/alerts', {
                onSuccess: () => this.$router.push({name: 'alerts.index'}),
                onFailure: () => this.$refs.name.focus()
            })
        },
    }
}
</script>
