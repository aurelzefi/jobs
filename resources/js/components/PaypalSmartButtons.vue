<template>
    <div :id="`paypal-smart-buttons-${elementId}`"></div>
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

            order: null
        }
    },

    mounted() {
        this.mountButtons()
    },

    methods: {
        mountButtons() {
            this.paypal.Buttons({
                createOrder: () => this.createOrder(),
                onApprove: () => this.onApprove()
            }).render(`#paypal-smart-buttons-${this.elementId}`)
        },

        createOrder() {
            return this.$http.post(`/api/jobs/${this.job.id}/orders`, {type: this.form.type})
                .then(response => {
                    this.order = response.data

                    return response.data.paypal_order_id
                })
                .catch(() => {
                    this.showDangerBanner()
                })
        },

        onApprove() {
            return this.$http.put(`/api/orders/${this.order.id}/capture`)
                .then(() => {
                    this.$emit('store:order')
                })
                .catch(() => {
                    this.showDangerBanner()
                })
        },

        showDangerBanner() {
            this.$root.banner.style = 'danger'
            this.$root.banner.message = this.__('The payment for this order has failed. Please try again.')
        }
    }
}
</script>
