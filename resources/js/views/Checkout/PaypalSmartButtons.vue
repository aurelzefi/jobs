<template>
    <div>
        <div :id="`paypal-smart-buttons-${elementId}`"></div>

        <div class="text-sm text-center text-gray-600" v-if="creating">
            {{ __('Please wait while we\'re processing your order...') }}
        </div>

        <div class="text-sm text-center text-gray-600" v-if="approving">
            {{ __('Please wait while we\'re finishing up with your order...') }}
        </div>
    </div>
</template>

<script>
export default {
    props: ['job', 'type'],

    data() {
        return {
            form: this.$form.create({
                type: this.type
            }),

            elementId: this.randomString(),

            order: null,

            creating: false,
            approving: false
        }
    },

    mounted() {
        this.mountButtons()
    },

    methods: {
        mountButtons() {
            this.paypal.Buttons({
                createOrder: () => this.createOrder(),
                onApprove: () => this.onApprove(),
                onCancel: () => {
                    this.creating = false
                    this.approving = false
                }
            }).render(`#paypal-smart-buttons-${this.elementId}`)
        },

        createOrder() {
            this.creating = true

            return this.$http.post(`/api/jobs/${this.job.id}/orders`, {type: this.form.type})
                .then(response => {
                    this.creating = false
                    this.order = response.data

                    return response.data.paypal_order_id
                })
                .catch(() => {
                    this.creating = false
                    this.showDangerBanner()
                })
        },

        onApprove() {
            this.approving = true

            return this.$http.put(`/api/orders/${this.order.id}/capture`)
                .then(() => {
                    this.approving = false

                    this.$router.push({name: 'jobs.all'})
                    this.$root.banner.message = this.__(
                        'Your order has been successfully completed. It is now listed on our Jobs page.'
                    )
                })
                .catch(() => {
                    this.approving = false
                    this.showDangerBanner()
                })
        },

        showDangerBanner() {
            this.$root.banner.style = 'danger'
            this.$root.banner.message = this.__('The payment for this order has failed. Please try again later.')
        }
    }
}
</script>
