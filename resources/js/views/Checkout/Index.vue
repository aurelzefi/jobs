<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Checkout') }}
            </h2>
        </template>

        <div v-if="job">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <template v-if="job.is_active">
                    <action-section>
                        <template #title>
                            {{ __('Basic Order') }}
                        </template>

                        <template #description>
                            {{ __('Description here') }}
                        </template>

                        <template #content>
                            <div class="max-w-xl text-sm text-gray-600">
                                {{ __('This job is already active. No action is required.') }}
                            </div>
                        </template>
                    </action-section>
                </template>

                <template v-else>
                    <div>
                        <create-basic-post-form :job="job" @store:order="updateJob" />

                        <section-border />
                    </div>

                    <div>
                        <create-pinned-post-form class="mt-10 sm:mt-0" :job="job" @store:order="updateJob" />

                        <section-border />
                    </div>
                </template>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ActionSection from '../../components/ActionSection'
import SectionBorder from '../../components/SectionBorder'
import AppLayout from '../../layouts/AppLayout'
import CreateBasicPostForm from './CreateBasicPostForm'
import CreatePinnedPostForm from './CreatePinnedPostForm'

export default {
    components: {
        ActionSection,
        SectionBorder,
        AppLayout,
        CreateBasicPostForm,
        CreatePinnedPostForm
    },

    props: ['jobId'],

    data() {
        return {
            job: null
        }
    },

    mounted() {
        this.getJob()
    },

    methods: {
        getJob() {
            this.$http.get(`/api/jobs/${this.jobId}`)
                .then(response => {
                    this.job = response.data
                })
        },

        updateJob() {
            this.getJob()

            this.$root.banner.message = this.__('Your order has been successfully completed. It is now listed on our Jobs page.')
        }
    }
}
</script>
