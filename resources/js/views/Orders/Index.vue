<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Orders') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <action-section>
                    <template #title>
                        {{ __('Orders') }}
                    </template>

                    <template #description>
                        {{ __('Description Here') }}
                    </template>

                    <template #content>
                        <div class="space-y-6" v-if="orders.length">
                            <div class="flex items-center justify-between" v-for="order in orders" :key="order.id">
                                <div>
                                    {{ order.job ? order.job.title : '-' }}
                                </div>

                                <div class="flex items-center">
                                    <div>
                                        {{ ucfirst(order.type) }}
                                    </div>

                                    <div class="ml-6">
                                        &euro;{{ order.amount / 100 }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            {{ __('You don\'t have any orders.') }}
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
            orders: []
        }
    },

    mounted() {
        this.getOrders()
    },

    methods: {
        getOrders() {
            this.$http.get('/api/orders')
                .then(response => {
                    this.orders = response.data
                })
        }
    }
}
</script>
