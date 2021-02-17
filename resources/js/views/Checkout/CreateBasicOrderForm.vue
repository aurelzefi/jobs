<template>
    <action-section>
        <template #title>
            {{ __('Basic Order') }}
        </template>

        <template #description>
            {{ __('The order will be posted on our dashboard.') }}
        </template>

        <template #content>
            <template v-if="App.user.free_orders_left > 0">
                <h2 class="text-4xl font-medium text-gray-900">
                    &euro;{{ App.orders.basic / 100 }}
                </h2>

                <h3 class="mt-3 text-lg font-medium text-gray-900">
                    {{ __('You are still eligible for free orders.') }}
                </h3>

                <div class="mt-3 max-w-xl text-sm text-gray-600">
                    {{ __('It is not necessary to pay for this order. Of course, if you decide to pay anyway, it will not affect the amount of free orders available to you.') }}
                </div>

                <div class="flex mt-5">
                    <app-button type="button" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.native="store">
                        {{ __('Post For Free') }}
                    </app-button>
                </div>
            </template>

            <div class="flex mt-5">
                <paypal-smart-buttons :job="job" type="basic"/>
            </div>
        </template>
    </action-section>
</template>

<script>
import ActionSection from '../../components/ActionSection'
import AppButton from '../../components/Button'
import PaypalSmartButtons from '../../components/PaypalSmartButtons'

export default {
    props: ['job'],

    components: {
        ActionSection,
        AppButton,
        PaypalSmartButtons
    },

    data() {
        return {
            form: this.$form.create({
                type: 'free'
            })
        }
    },
    methods: {
        store() {
            this.form.post(`/api/jobs/${this.job.id}/orders`, {
                onSuccess: () => {
                    this.$root.banner.message = this.__('Your order has been successfully completed.')

                    this.$router.push({name: 'jobs.all'})
                }
            })
        },
    }
}
</script>
