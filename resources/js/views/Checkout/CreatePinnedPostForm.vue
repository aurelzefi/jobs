<template>
    <action-section>
        <template #title>
            {{ __('Pinned Post') }}
        </template>

        <template #description>
            {{ __('The job will be posted and will be pinned at the top. It will remain active for 30 days. No worries, we will notify you when the job is about to expire so you can renew it if you want.') }}
        </template>

        <template #content>
            <div class="flex items-center">
                <h2 class="text-4xl font-medium text-gray-900">
                    &euro;{{ App.orders.pinned / 100 }}
                </h2>

                <span class="ml-2 text-sm text-gray-600">
                    {{ __('for 30 days') }}
                </span>
            </div>

            <div class="max-w-sm mt-5">
                <paypal-smart-buttons :job="job" type="pinned" />
            </div>
        </template>
    </action-section>
</template>

<script>
import ActionSection from '../../components/ActionSection'
import PaypalSmartButtons from '../../components/PaypalSmartButtons'

export default {
    components: {
        ActionSection,
        PaypalSmartButtons
    },

    props: ['job'],
}
</script>
