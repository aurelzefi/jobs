<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('All Jobs') }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="md:flex items-center justify-between">
                            <form @submit.prevent="pushRouter">
                                <div class="md:flex items-center">
                                    <app-input id="query" type="text" class="min-w-full" :placeholder="__('Search by job title or description')" v-model="form.query" />

                                    <app-button class="mt-2 md:mt-0 md:ml-2">
                                        {{ __('Search') }}
                                    </app-button>
                                </div>
                            </form>

                            <div class="mt-2 md:mt-0">
                                <app-button type="button" @click.native="showSearchModal">
                                    {{ __('Advanced Search') }}
                                </app-button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 bg-white overflow-hidden shadow-sm sm:rounded-lg" v-if="jobs.length">
                    <router-link v-for="job in jobs" :key="job.id" :to="{name: 'jobs.show', params: {jobId: job.id}}" class="block p-4 bg-white hover:bg-gray-50 border-b border-gray-200">
                        <div class="flex justify-between">
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

                <div v-else>
                    <div class="mt-4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            {{ __('No jobs for this search.') }}
                        </div>
                    </div>
                </div>

                <div class="mt-4" v-if="paginator">
                    <pagination :paginator="paginator" :params="form.getData()" />
                </div>

                <dialog-modal :show="showingSearchModal" @close="closeModal">
                    <template #title>
                        {{ __('Search Jobs') }}
                    </template>

                    <template #content>
                        <form @submit.prevent="pushRouter">
                            <div class="col-span-6 sm:col-span-4">
                                <app-label for="title">{{ __('Title') }}</app-label>
                                <app-input id="title" type="text" class="mt-1 block w-full" v-model="form.title" ref="title" />
                            </div>

                            <div class="mt-6 col-span-6 sm:col-span-4">
                                <app-label for="description">{{ __('Description') }}</app-label>
                                <app-input id="description" type="text" class="mt-1 block w-full" v-model="form.description" />
                            </div>

                            <div class="mt-6 col-span-6 sm:col-span-4">
                                <app-label for="company">{{ __('Company') }}</app-label>
                                <app-input id="company" type="text" class="mt-1 block w-full" v-model="form.company" />
                            </div>

                            <div class="mt-6 col-span-6 sm:col-span-4">
                                <app-label for="city">{{ __('City') }}</app-label>
                                <app-input id="city" type="text" class="mt-1 block w-full" v-model="form.city" />
                            </div>

                            <div class="mt-6 col-span-6 sm:col-span-4">
                                <app-label for="country">{{ __('Country') }}</app-label>
                                <app-select id="country" class="mt-1 block w-full" :options="countries" :default-option="__('Select a country')" v-model="form.country_id" />
                            </div>

                            <div class="mt-6 col-span-6 sm:col-span-4">
                                <app-label for="keywords">{{ __('Keywords') }}</app-label>
                                <app-input id="keywords" type="text" class="mt-1 block w-full" v-model="form.keywords" />
                                <secondary-text>{{ __('A comma separated list of keywords.') }}</secondary-text>
                            </div>

                            <div class="mt-6 col-span-6 sm:col-span-4">
                                <app-label for="has-all-keywords">
                                    <div class="flex items-center">
                                        <app-checkbox id="has-all-keywords" v-model="form.has_all_keywords" />

                                        <div class="ml-2">
                                            {{ __('Should Contain All Keywords') }}
                                        </div>
                                    </div>
                                </app-label>
                            </div>

                            <div class="mt-6 col-span-6 sm:col-span-4">
                                <app-label for="job-types">
                                    {{ __('Job Types') }}
                                </app-label>

                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div v-for="type in App.jobTypes" :key="type">
                                        <label class="flex items-center">
                                            <app-checkbox :value="type" v-model="form.types"/>
                                            <span class="ml-2 text-sm text-gray-600">{{ __(type) }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 col-span-6 sm:col-span-4">
                                <app-label for="job-types">
                                    {{ __('Job Styles') }}
                                </app-label>

                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div v-for="style in App.jobStyles" :key="style">
                                        <label class="flex items-center">
                                            <app-checkbox :value="style" v-model="form.styles"/>
                                            <span class="ml-2 text-sm text-gray-600">{{ __(style) }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

<!--                            <div class="mt-6 col-span-6 sm:col-span-4">-->
<!--                                <div class="grid grid-cols-2">-->
<!--                                    <div class="col-span-1">-->
<!--                                        <app-label for="from-added-date">{{ __('From Added Date') }}</app-label>-->
<!--                                        <app-input id="added-from-date" type="date" class="mt-1 block w-full" v-model="form.from_added_at" />-->
<!--                                    </div>-->

<!--                                    <div class="ml-2 col-span-1">-->
<!--                                        <app-label for="to-added-date">{{ __('To Added Date') }}</app-label>-->
<!--                                        <app-input id="to-added-date" type="date" class="mt-1 block w-full" v-model="form.to_added_at" />-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->

                            <button type="submit" class="hidden"></button>
                        </form>
                    </template>

                    <template #footer>
                        <secondary-button @click.native="closeModal">
                            {{ __('Close') }}
                        </secondary-button>

                        <app-button class="ml-2" @click.native="pushRouter">
                            {{ __('Search') }}
                        </app-button>
                    </template>
                </dialog-modal>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppButton from '../components/Button'
import AppCheckbox from '../components/Checkbox'
import CompanyIcon from '../components/CompanyIcon'
import DialogModal from '../components/DialogModal'
import AppInput from '../components/Input'
import AppLabel from '../components/Label'
import Pagination from '../components/Pagination'
import SecondaryButton from '../components/SecondaryButton'
import SecondaryText from '../components/SecondaryText'
import AppSelect from '../components/Select'
import AppLayout from '../layouts/AppLayout'

export default {
    components: {
        AppButton,
        AppCheckbox,
        CompanyIcon,
        DialogModal,
        AppInput,
        AppLabel,
        Pagination,
        SecondaryButton,
        SecondaryText,
        AppSelect,
        AppLayout
    },

    data() {
        return {
            jobs: [],
            paginator: null,
            showingSearchModal: false,

            form: this.$form.create({
                query: this.$route.query.query ?? '',
                company: this.$route.query.company ?? '',
                country_id: this.$route.query.country_id ?? '',
                title: this.$route.query.title ?? '',
                description: this.$route.query.description ?? '',
                city: this.$route.query.city ?? '',
                types: this.$route.query.types ?? '',
                styles: this.$route.query.styles ?? '',
                has_all_keywords: this.$route.query.has_all_keywords ?? '',
                keywords: this.$route.query.keywords ?? '',
                from_added_at: this.$route.query.from_added_at ?? '',
                to_added_at: this.$route.query.to_added_at ?? '',
                page: this.$route.query.page ?? 1,
            }),

            countries: {}
        }
    },

    beforeRouteEnter(to, from, next) {
        axios.get('/api/jobs/all')
            .then(jobs => {
                next(vm => {
                    vm.paginator = jobs.data
                    vm.jobs = jobs.data.data
                })
            })
    },

    watch: {
        '$route' () {
            this.form.page = this.$route.query.page

            this.getJobs()
        }
    },

    mounted() {
        this.form.types = this.App.jobTypes
        this.form.styles = this.App.jobStyles
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

        pushRouter() {
            this.showingSearchModal = false

            if (this.noChanges()) {
                return
            }

            this.$router.push({
                name: 'jobs.all',
                query: this.form.getData()
            })
        },

        noChanges() {
            return JSON.stringify(this.form.getData()) === JSON.stringify(this.$route.query)
        },

        showSearchModal() {
            this.showingSearchModal = true

            setTimeout(() => {
                this.$refs.title.focus()
            }, 200)
        },

        closeModal() {
            this.showingSearchModal = false
        }
    }
}
</script>
