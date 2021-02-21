<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Jobs') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <table-section :count="jobs.length" :headers="tableHeaders" :has-table-actions="true">
                    <template #actions>
                        <action-link :to="{name: 'jobs.create'}">
                            {{ __('Create New Job') }}
                        </action-link>
                    </template>

                    <template #body>
                        <tr v-for="job in jobs">
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ job.title }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800" v-if="job.active_order && ! job.expires_today">
                                    {{ __('Active') }}
                                </span>

                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-pink-100 text-pink-800" v-if="job.expires_today">
                                    {{ __('Expires Today') }}
                                </span>

                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800" v-if="! job.active_order">
                                    {{ __('Inactive') }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ date(job.active_order.paid_at) }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <router-link :to="{name: 'jobs.checkout', params: {jobId: job.id}}" class="text-indigo-600 hover:text-indigo-900" v-if="job.active_order && job.expires_today || ! job.active_order">
                                    {{ __('Renew') }}
                                </router-link>

                                <router-link :to="{name: 'jobs.edit', params: {job: job.id}}" class="ml-6 text-indigo-600 hover:text-indigo-900">
                                    {{ __('Edit') }}
                                </router-link>

                                <button class="ml-6 text-red-600 hover:text-red-900 focus:outline-none" @click="confirmJobDeletion(job)">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                    </template>

                    <template #empty>
                        {{ __('You don\'t have any jobs.') }}
                    </template>

                    <dialog-modal :show="confirmingJobDeletion" @close="closeModal">
                        <template #title>
                            {{ __('Delete Job') }}
                        </template>

                        <template #content>
                            {{ __('Are you sure you want to delete this job?') }}
                        </template>

                        <template #footer>
                            <secondary-button @click.native="closeModal">
                                {{ __('Close') }}
                            </secondary-button>

                            <danger-button class="ml-2" @click.native="deleteJob" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                {{ __('Delete Job') }}
                            </danger-button>
                        </template>
                    </dialog-modal>
                </table-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ActionLink from '../../components/ActionLink'
import DangerButton from '../../components/DangerButton'
import DialogModal from '../../components/DialogModal'
import SecondaryButton from '../../components/SecondaryButton'
import TableSection from '../../components/TableSection'
import AppLayout from '../../layouts/AppLayout'

export default {
    components: {
        ActionLink,
        DangerButton,
        DialogModal,
        SecondaryButton,
        TableSection,
        AppLayout
    },

    data() {
        return {
            jobs: [],

            tableHeaders: [
                'Title',
                'Active',
                'Added At'
            ],

            currentJob: null,
            confirmingJobDeletion: false,
            form: this.$form.create({})
        }
    },

    beforeRouteEnter(to, from, next) {
        axios.get('/api/jobs')
            .then(response => {
                next(vm => {
                    vm.jobs = response.data
                })
            })
    },

    methods: {
        getJobs() {
            this.$http.get('/api/jobs')
                .then(response => {
                    this.jobs = response.data
                })
        },

        confirmJobDeletion(job) {
            this.currentJob = job
            this.confirmingJobDeletion = true
        },

        deleteJob() {
            this.form.delete(`/api/jobs/${this.currentJob.id}`, {
                onSuccess: () => {
                    this.getJobs()
                    this.closeModal()
                }
            })
        },

        closeModal() {
            this.confirmingJobDeletion = false
        }
    }
}
</script>
