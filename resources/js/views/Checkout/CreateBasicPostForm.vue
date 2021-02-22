<template>
    <action-section>
        <template #title>
            {{ __('Basic Post') }}
        </template>

        <template #description>
            {{ __('The job will be posted and will remain active for 30 days. No worries, we will notify you when the job is about to expire so you can renew it if you want.') }}
        </template>

        <template #content>
            <div class="flex items-center">
                <h2 class="text-4xl font-medium text-gray-900">
                    &euro;{{ App.orders.basic / 100 }}
                </h2>

                <span class="ml-2 text-sm text-gray-600">
                    {{ __('for 30 days') }}
                </span>
            </div>

            <template v-if="App.user.free_orders_left > 0">
                <h3 class="mt-3 text-lg font-medium text-gray-900">
                    {{ __('You are still eligible for free orders.') }}
                </h3>

                <div class="mt-3 max-w-xl text-sm text-gray-600">
                    <p>
                        {{ __('It is not necessary to pay for this order. Of course, if you decide to pay anyway, it will not affect the amount of free orders available to you.') }}
                    </p>
                </div>

                <div class="flex mt-5">
                    <app-button type="button" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click.native="store">
                        {{ __('Post For Free') }}
                    </app-button>
                </div>
            </template>

            <div class="max-w-sm mt-5">
                <paypal-smart-buttons :job="job" type="basic" />
            </div>
        </template>
    </action-section>
</template>

<script>
import ActionSection from '../../components/ActionSection'
import AppButton from '../../components/Button'
import PaypalSmartButtons from './PaypalSmartButtons'

export default {
    components: {
        ActionSection,
        AppButton,
        PaypalSmartButtons
    },

    props: ['job'],

    data() {
        return {
            form: this.$form.create({})
        }
    },
    methods: {
        store() {
            this.form.post(`/api/jobs/${this.job.id}/orders/free`, {
                onSuccess: () => {
                    this.$router.push({name: 'jobs.all'})

                    this.$root.banner.message = this.__(
                        'Your order has been successfully completed. It is now listed on our Jobs page.'
                    )
                }
            })
        },
    }
}
</script>
