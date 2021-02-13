export default {
    computed: {
        App() {
            return window.App;
        },
    },

    methods: {
        __(string) {
            return this.$t(string);
        },
    },
}
