<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('All Jobs') }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <router-link v-for="job in jobs" :key="job.id" :to="{name: 'jobs.show', params: {job: job.id}}" class="block p-4 bg-white hover:bg-gray-50 border-b border-gray-200">
                        <div class="flex justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-14 w-14" v-if="job.company.logo">
                                    <img class="h-14 w-14 rounded-full" :src="imageUrl(job.company.logo)" :alt="job.company.logo">
                                </div>

                                <div class="text-gray-500" v-else>
                                    <company-icon class="h-14 w-14" />
                                </div>

                                <div class="ml-4">
                                    <div class="text-gray-900">
                                        {{ job.title }}
                                    </div>

                                    <div class="mt-1 text-sm text-gray-500">
                                        {{ job.company.name }}
                                    </div>

                                    <div class="mt-1 text-sm text-gray-500">
                                        {{ `${__(job.type)} &#8226; ${__(job.style)} &#8226; ${job.city}` }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="text-gray-500" v-if="job.pinned" :title="__('Pinned')">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                                    </svg>
                                </div>

                                <div class="ml-6 text-sm text-gray-900" :title="readableTimestamp(job.last_paid_at)">
                                    {{ dateForHumans(job.last_paid_at) }}
                                </div>
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import CompanyIcon from '../components/CompanyIcon'
import AppLayout from '../layouts/AppLayout'

export default {
    components: {CompanyIcon, AppLayout},

    data() {
        return {
            jobs: [],

            form: this.$form.create({
                query: '',
                company: '',
                country_id: '',
                title: '',
                description: '',
                city: '',
                types: [],
                styles: [],
                has_all_keywords: true,
                keywords: '',
                from_added_at: '',
                to_added_at: ''
            })
        }
    },

    mounted() {
        this.getJobs()
    },

    methods: {
        getJobs() {
            this.form.get('/api/jobs/all', {
                onSuccess: response => {
                    this.jobs = response.data.data
                }
            })
        }
    }
}
</script>
