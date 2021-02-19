<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Alerts') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <table-section :count="alerts.length" :headers="tableHeaders" :has-table-actions="true">
                    <template #actions>
                        <action-link :to="{name: 'alerts.create'}">
                            {{ __('Create New Alert') }}
                        </action-link>
                    </template>

                    <template #body>
                        <tr v-for="alert in alerts">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ alert.name }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ __(alert.type) }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ date(alert.created_at) }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <router-link :to="{name: 'alerts.edit', params: {alert: alert.id}}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ __('Edit') }}
                                </router-link>

                                <button class="ml-6 text-red-600 hover:text-red-900 focus:outline-none" @click="confirmAlertDeletion(alert)">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                    </template>

                    <template #empty>
                        {{ __('You don\'t have any alerts.') }}
                    </template>

                    <dialog-modal :show="confirmingAlertDeletion" @close="closeModal">
                        <template #title>
                            {{ __('Delete Alert') }}
                        </template>

                        <template #content>
                            {{ __('Are you sure you want to delete this alert?') }}
                        </template>

                        <template #footer>
                            <secondary-button @click.native="closeModal">
                                {{ __('Close') }}
                            </secondary-button>

                            <danger-button class="ml-2" @click.native="deleteAlert" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                {{ __('Delete Alert') }}
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
            alerts: [],

            tableHeaders: [
                'Name',
                'Type',
                'Created At'
            ],

            currentAlert: null,
            confirmingAlertDeletion: false,
            form: this.$form.create({})
        }
    },

    mounted() {
        this.getAlerts()
    },

    methods: {
        getAlerts() {
            this.$http.get('/api/alerts')
                .then(response => {
                    this.alerts = response.data
                })
        },

        confirmAlertDeletion(alert) {
            this.currentAlert = alert
            this.confirmingAlertDeletion = true
        },

        deleteAlert() {
            this.form.delete(`/api/alerts/${this.currentAlert.id}`, {
                onSuccess: () => {
                    this.getAlerts()
                    this.closeModal()
                }
            })
        },

        closeModal() {
            this.confirmingAlertDeletion = false
        }
    }
}
</script>
