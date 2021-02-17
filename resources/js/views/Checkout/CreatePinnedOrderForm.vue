<template>
    <action-section>
        <template #title>
            {{ __('Pinned Order') }}
        </template>

        <template #description>
            {{ __('The order will be posted on our dashboard and it will be pinned at the top.') }}
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                <h2 class="text-4xl font-medium text-gray-900">
                    &euro;{{ App.orders.pinned / 100 }}
                </h2>
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
    props: ['job'],

    components: {
        ActionSection,
        PaypalSmartButtons
    }
}
</script>
