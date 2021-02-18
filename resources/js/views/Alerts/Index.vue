<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Alerts') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <action-section>
                    <template #title>
                        {{ __('Alerts') }}
                    </template>

                    <template #description>
                        {{ __('Description Here') }}
                    </template>

                    <template #actions>
                        <action-link :to="{name: 'alerts.create'}">
                            {{ __('Create New Alert') }}
                        </action-link>
                    </template>

                    <template #content>
                        <div class="space-y-6" v-if="alerts.length">
                            <div class="flex items-center justify-between" v-for="alert in alerts" :key="alert.id">
                                <div>
                                    {{ alert.name }}
                                </div>

                                <div class="flex items-center">
                                    <router-link :to="{name: 'alerts.edit', params: {alert: alert.id}}" class="cursor-pointer ml-6 text-sm text-gray-400 underline">
                                        {{ __('Edit') }}
                                    </router-link>

                                    <button class="cursor-pointer ml-6 text-sm text-red-500" @click="confirmAlertDeletion(alert)">
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            {{ __('You don\'t have any alerts.') }}
                        </div>

                        <dialog-modal :show="confirmingAlertDeletion" @close="closeModal">
                            <template #title>
                                {{ __('Delete Alert') }}
                            </template>

                            <template #content>
                                {{ __('Are you sure you want to delete this alert?') }}
                            </template>

                            <template #footer>
                                <secondary-button @click.native="closeModal">
                                    {{ __('Nevermind') }}
                                </secondary-button>

                                <danger-button class="ml-2" @click.native="deleteAlert" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    {{ __('Delete Alert') }}
                                </danger-button>
                            </template>
                        </dialog-modal>
                    </template>
                </action-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ActionLink from '../../components/ActionLink'
import ActionSection from '../../components/ActionSection'
import DangerButton from '../../components/DangerButton'
import DialogModal from '../../components/DialogModal'
import SecondaryButton from '../../components/SecondaryButton'
import AppLayout from '../../layouts/AppLayout'

export default {
    components: {
        ActionLink,
        ActionSection,
        DangerButton,
        DialogModal,
        SecondaryButton,
        AppLayout
    },

    data() {
        return {
            alerts: [],

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
