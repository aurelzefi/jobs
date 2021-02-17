export default {
    data() {
        return {
            App: window.App,
            paypal: window.paypal
        }
    },

    methods: {
        __(string) {
            return this.$t(string)
        },

        keyByValues(values) {
            return values.reduce((accumulator, current) => {
                accumulator[current] = current

                return accumulator
            }, {})
        },
    }
}
