<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Orders') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <table-section :count="orders.length" :headers="tableHeaders">
                    <template #body>
                        <tr v-for="order in orders">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ order.paypal_order_id ? order.paypal_order_id : '-' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ order.job ? order.job.title : '-' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    &euro;{{ order.amount / 100 }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ __(order.type) }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ date(order.paid_at) }}
                                </div>
                            </td>
                        </tr>
                    </template>

                    <template #empty>
                        {{ __('You don\'t have any orders.') }}
                    </template>
                </table-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ActionLink from '../../components/ActionLink'
import TableSection from '../../components/TableSection'
import AppLayout from '../../layouts/AppLayout'

export default {
    components: {
        ActionLink,
        TableSection,
        AppLayout
    },

    data() {
        return {
            orders: [],

            tableHeaders: [
                'Paypal ID',
                'Job',
                'Amount',
                'Type',
                'Completed At'
            ]
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
