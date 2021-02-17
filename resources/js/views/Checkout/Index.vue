<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Checkout') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div>
                    <create-basic-order-form :job="job" />

                    <section-border />
                </div>

                <div>
                    <create-pinned-order-form class="mt-10 sm:mt-0" :job="job" />

                    <section-border />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import SectionBorder from '../../components/SectionBorder'
import AppLayout from '../../layouts/AppLayout'
import CreateBasicOrderForm from './CreateBasicOrderForm'
import CreatePinnedOrderForm from './CreatePinnedOrderForm'

export default {
    props: ['job'],

    components: {
        SectionBorder,
        AppLayout,
        CreateBasicOrderForm,
        CreatePinnedOrderForm
    }
}
</script>
