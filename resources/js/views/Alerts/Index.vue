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

                                    <button class="cursor-pointer ml-6 text-sm text-red-500" @click="destroy">
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            {{ __('You have not created any alerts yet.') }}
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
            alerts: []
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

        destroy() {

        }
    }
}
</script>
