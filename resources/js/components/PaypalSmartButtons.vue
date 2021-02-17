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
                createOrder: () => {
                    return this.$http.post(`/api/jobs/${this.job}/orders`, {type: this.form.type})
                        .then(response => {
                            this.order = response.data

                            return response.data.paypal_order_id
                        })
                },
                onApprove: () => {
                    return this.$http.put(`/api/orders/${this.order.id}/capture`)
                        .then(() => {
                            this.$router.push({name: 'jobs.all'})
                        })
                }
            }).render(`#paypal-smart-buttons-${this.elementId}`)
        },

        randomString(length = 5) {
            let randomChars = 'abcdefghijklmnopqrstuvwxyz'

            let result = ''

            for (let i = 0; i < length; i++) {
                result += randomChars.charAt(Math.floor(Math.random() * randomChars.length))
            }

            return result
        }
    }
}
</script>
