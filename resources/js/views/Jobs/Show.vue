<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Job Profile') }}
            </h2>
        </template>

        <div v-if="job">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <action-section>
                    <template #title>
                        {{ __('Job Information') }}
                    </template>

                    <template #description>
                        {{ __('Here you will find all the information you need regarding the job description.') }}
                    </template>

                    <template #content>
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <router-link :to="{name: 'companies.show', params: {companyId: job.company_id}}" class="flex-shrink-0 h-14 w-14" v-if="job.company.logo">
                                        <img class="h-14 w-14 rounded-full" :src="imageUrl(job.company.logo)" :alt="job.company.logo">
                                    </router-link>

                                    <router-link :to="{name: 'companies.show', params: {companyId: job.company_id}}" class="text-gray-500" v-else>
                                        <company-icon class="h-14 w-14" />
                                    </router-link>

                                    <div class="ml-4">
                                        <div class="text-gray-900">
                                            {{ job.title }}
                                        </div>

                                        <router-link :to="{name: 'companies.show', params: {companyId: job.company_id}}" class="mt-1 block text-sm text-gray-500 hover:underline">
                                            {{ job.company.name }}
                                        </router-link>

                                        <div class="flex mt-1 text-sm text-gray-500">
                                            <span>{{ `${__(job.type)} &#8226; ${__(job.style)} &#8226;` }}</span>

                                            <div class="flex items-center ml-1">
                                                <location-icon class="h-4 w-4 -mt-1" />
                                                <span>{{ job.city }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="ml-4 text-sm text-gray-900" :title="readableTimestamp(job.active_order.paid_at)">
                                    {{ dateForHumans(job.active_order.paid_at) }}
                                </div>
                            </div>
                        </div>

                        <div class="p-4" v-html="job.description"></div>
                    </template>
                </action-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ActionSection from '../../components/ActionSection'
import CompanyIcon from '../../components/CompanyIcon'
import LocationIcon from '../../components/LocationIcon'
import AppLayout from '../../layouts/AppLayout'

export default {
    props: ['jobId'],

    components: {
        ActionSection,
        CompanyIcon,
        LocationIcon,
        AppLayout
    },

    data() {
        return {
            job: null
        }
    },

    beforeRouteEnter(to, from, next) {
        axios.get(`/api/jobs/${to.params.jobId}`)
            .then(response => {
                next(vm => {
                    vm.job = response.data
                })
            })
    }
}
</script>
