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
            window.paypal.Buttons({
                createOrder: () => this.createOrder(),
                onApprove: () => this.onApprove()
            }).render(`#paypal-smart-buttons-${this.elementId}`)
        },

        randomString(length = 5) {
            let randomChars = 'abcdefghijklmnopqrstuvwxyz'
            let result = ''

            for (let i = 0; i < length; i++) {
                result += randomChars.charAt(Math.floor(Math.random() * randomChars.length))
            }

            return result
        },

        createOrder() {
            return this.$http.post(`/api/jobs/${this.job.id}/orders`, {type: this.form.type})
                .then(response => {
                    this.order = response.data

                    return response.data.paypal_order_id
                })
                .catch(() => {
                    this.$root.banner.style = 'danger'
                    this.$root.banner.message = this.__('The payment for this order has failed. Please try again.')
                })
        },

        onApprove() {
            return this.$http.put(`/api/orders/${this.order.id}/capture`)
                .then(() => {
                    this.$root.banner.message = this.__('Your order has been successfully completed.')

                    this.$router.push({name: 'jobs.all'})
                })
                .catch(() => {
                    this.$root.banner.style = 'danger'
                    this.$root.banner.message = this.__('The payment for this order has failed. Please try again.')
                })
        }
    }
}
</script>
