export default {
    data() {
        return {
            App: window.App
        }
    },

    methods: {
        __(string) {
            return this.$t(string)
        }
    }
}
