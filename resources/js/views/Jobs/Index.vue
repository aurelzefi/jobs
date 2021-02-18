<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Jobs') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <action-section>
                    <template #title>
                        {{ __('Jobs') }}
                    </template>

                    <template #description>
                        {{ __('Description Here') }}
                    </template>

                    <template #actions>
                        <action-link :to="{name: 'jobs.create'}">
                            {{ __('Create New Job') }}
                        </action-link>
                    </template>

                    <template #content>
                        <div class="space-y-6" v-if="jobs.length">
                            <div class="flex items-center justify-between" v-for="job in jobs" :key="job.id">
                                <div>
                                    {{ job.title }}
                                </div>

                                <div class="flex items-center">
                                    <div>
                                        {{ job.is_active ? __('Active') : __('Inactive') }}
                                    </div>

                                    <router-link :to="{name: 'jobs.edit', params: {job: job.id}}" class="cursor-pointer ml-6 text-sm text-gray-400 underline">
                                        {{ __('Edit') }}
                                    </router-link>

                                    <button class="cursor-pointer ml-6 text-sm text-red-500" @click="destroy">
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            {{ __('You have not created any jobs yet.') }}
                        </div>
                    </template>
                </action-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ActionLink from '../../components/ActionLink'
import ActionSection from '../../components/ActionSection'
import AppLayout from '../../layouts/AppLayout'

export default {
    components: {
        ActionLink,
        ActionSection,
        AppLayout
    },

    data() {
        return {
            jobs: []
        }
    },

    mounted() {
        this.getJobs()
    },

    methods: {
        getJobs() {
            this.$http.get('/api/jobs')
                .then(response => {
                    this.jobs = response.data
                })
        },

        destroy() {

        }
    }
}
</script>
