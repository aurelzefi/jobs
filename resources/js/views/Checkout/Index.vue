<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Checkout') }}
            </h2>
        </template>

        <div v-if="job">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" v-if="job.active_order">
                    <div class="p-6 bg-white border-b border-gray-200">
                        {{ __('This job is already active. No action is required.') }}
                    </div>
                </div>

                <template v-else>
                    <div>
                        <create-basic-post-form :job="job" />

                        <section-border />
                    </div>

                    <div>
                        <create-pinned-post-form class="mt-10 sm:mt-0" :job="job" />

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
