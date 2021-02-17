<template>
    <action-section>
        <template #title>
            {{ __('Pinned Order') }}
        </template>

        <template #description>
            {{ __('Description here') }}
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">

            </div>

            <div class="flex mt-5">
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
