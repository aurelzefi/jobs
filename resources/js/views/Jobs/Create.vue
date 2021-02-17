<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Create Job') }}
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
                            <app-label for="title">{{ __('Title') }}</app-label>
                            <app-input id="title" type="text" class="mt-1 block w-full" v-model="form.title" />
                            <app-input-error :message="form.errors.title" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="description">{{ __('Description') }}</app-label>
                            <vue-editor v-model="form.description" />
                            <app-input-error :message="form.errors.description" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="company">{{ __('Company') }}</app-label>
                            <company-select id="company" class="mt-1 block w-full" v-model="form.company_id" />
                            <app-input-error :message="form.errors.company_id" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="type">{{ __('Type') }}</app-label>
                            <app-select id="type" class="mt-1 block w-full" :options="jobTypes" :default-option="__('Select a type')" v-model="form.type" />
                            <app-input-error :message="form.errors.type" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="style">{{ __('Style') }}</app-label>
                            <app-select id="style" class="mt-1 block w-full" :options="jobStyles" :default-option="__('Select a style')" v-model="form.style" />
                            <app-input-error :message="form.errors.style" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="city">{{ __('City') }}</app-label>
                            <app-input id="city" type="text" class="mt-1 block w-full" v-model="form.city" />
                            <app-input-error :message="form.errors.city" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="country">{{ __('Country') }}</app-label>
                            <country-select id="country" class="mt-1 block w-full" v-model="form.country_id" />
                            <app-input-error :message="form.errors.country_id" class="mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <div class="text-sm text-gray-600" v-if="App.user.free_orders_left > 0">
                            {{ __(`You are eligible for ${App.user.free_orders_left} more free orders`) }}
                        </div>

                        <app-button class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.native="checkout = false">
                            {{ __('Save') }}
                        </app-button>

                        <app-button class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.native="checkout = true">
                            {{ __('Checkout') }}
                        </app-button>
                    </template>
                </form-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import {VueEditor} from 'vue2-editor'
import ActionMessage from '../../components/ActionMessage'
import AppButton from '../../components/Button'
import AppCheckbox from '../../components/Checkbox'
import CompanySelect from '../../components/CompanySelect'
import CountrySelect from '../../components/CountrySelect'
import FormSection from '../../components/FormSection'
import AppInput from '../../components/Input'
import AppInputError from '../../components/InputError'
import AppLabel from '../../components/Label'
import PaypalSmartButtons from '../../components/PaypalSmartButtons'
import SectionBorder from '../../components/SectionBorder'
import AppSelect from '../../components/Select'
import AppLayout from '../../layouts/AppLayout'

export default {
    components: {
        VueEditor,
        ActionMessage,
        AppButton,
        AppCheckbox,
        CompanySelect,
        CountrySelect,
        FormSection,
        AppInput,
        AppInputError,
        AppLabel,
        PaypalSmartButtons,
        SectionBorder,
        AppSelect,
        AppLayout
    },

    data() {
        return {
            form: this.$form.create({
                company_id: '',
                country_id: '',
                title: '',
                description: '',
                city: '',
                type: '',
                style: ''
            }),

            jobTypes: {},
            jobStyles: {},

            checkout: false
        }
    },

    mounted() {
        this.jobTypes = this.keyByValues(this.App.jobTypes)
        this.jobStyles = this.keyByValues(this.App.jobStyles)
    },

    methods: {
        store() {
            this.form.post('/api/jobs', {
                onSuccess: () => {
                    this.$router.push({
                        name: this.checkout ? 'checkout.index' : 'jobs.index'
                    })
                }
            })
        },
    }
}
</script>
