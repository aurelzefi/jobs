<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Company Profile') }}
            </h2>
        </template>

        <div v-if="company">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <action-section>
                    <template #title>
                        {{ __('Company Information') }}
                    </template>

                    <template #description>
                        {{ __('Here you will find all the information you need regarding the company.') }}
                    </template>

                    <template #content>
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-14 w-14" v-if="company.logo">
                                        <img class="h-14 w-14 rounded-full" :src="imageUrl(company.logo)" :alt="company.logo">
                                    </div>

                                    <div class="text-gray-500 h-14 w-14" v-else>
                                        <company-icon class="h-14 w-14" />
                                    </div>

                                    <div class="ml-4">
                                        <div class="text-gray-900">
                                            {{ company.name }}
                                        </div>

                                        <div class="mt-1 text-sm text-gray-500">
                                            <a :href="company.website" v-if="company.website" target="_blank" class="cursor-pointer hover:underline">{{ __('Website') }}</a>

                                            <span v-if="company.website">&#8226;</span>

                                            {{ `${company.address}, ${company.city}, ${company.country.name}` }}
                                        </div>

                                        <div class="mt-1 text-sm text-gray-500">
                                            {{ __('Created on {date}', {date: moment(company.created_at).format('MMMM YYYY')}) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-4" v-html="company.description"></div>
                    </template>
                </action-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ActionSection from '../../components/ActionSection'
import CompanyIcon from '../../components/CompanyIcon'
import AppLayout from '../../layouts/AppLayout'

export default {
    props: ['companyId'],

    components: {
        ActionSection,
        CompanyIcon,
        AppLayout
    },

    data() {
        return {
            company: null
        }
    },

    beforeRouteEnter(to, from, next) {
        axios.get(`/api/companies/${to.params.companyId}`)
            .then(response => {
                next(vm => {
                    vm.company = response.data
                })
            })
    }
}
</script>
