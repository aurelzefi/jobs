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

                                <div class="ml-4 text-sm text-gray-900" :title="readableTimestamp(job.last_paid_at)">
                                    {{ dateForHumans(job.last_paid_at) }}
                                </div>
                            </div>
                        </div>
                    </router-link>
                </div>

                <div class="mt-4" v-if="paginator">
                    <pagination :paginator="paginator" :params="form.getData()" @update:page="getPage" />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import CompanyIcon from '../components/CompanyIcon'
import AppLayout from '../layouts/AppLayout'
import Pagination from '../components/Pagination'

export default {
    components: {Pagination, CompanyIcon, AppLayout},

    data() {
        return {
            jobs: [],
            paginator: null,

            form: this.$form.create({
                query: this.$route.query.query,
                company: this.$route.query.company,
                country_id: this.$route.query.country_id,
                title: this.$route.query.title,
                description: this.$route.query.description,
                city: this.$route.query.city,
                types: this.$route.query.types,
                styles: this.$route.query.styles,
                has_all_keywords: this.$route.query.has_all_keywords,
                keywords: this.$route.query.keywords,
                from_added_at: this.$route.query.from_added_at,
                to_added_at: this.$route.query.to_added_at,
                page: this.$route.query.page ?? 1,
            })
        }
    },

    watch: {
        '$route' () {
            this.getJobs()
        }
    },

    mounted() {
        this.getJobs()
    },

    methods: {
        getJobs() {
            this.form.get('/api/jobs/all', {
                onSuccess: response => {
                    this.paginator = response.data
                    this.jobs = response.data.data
                }
            })
        },

        getPage(page) {
            this.form.page = page

            this.$router.push({
                name: 'jobs.all',
                query: this.form.getData()
            })
        }
    }
}
</script>
