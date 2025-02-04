import moment from 'moment'
import _ from 'lodash'

export default {
    data() {
        return {
            App: window.App,
            paypal: window.paypal,

            lodash: _,

            countries: _.mapValues(window.App.countries, country => country.name)
        }
    },

    methods: {
        __(string, parameters) {
            return this.$t(string, parameters)
        },

        keyByValues(values) {
            return values.reduce((accumulator, current) => {
                accumulator[current] = current

                return accumulator
            }, {})
        },

        randomString(length = 5) {
            let randomChars = 'abcdefghijklmnopqrstuvwxyz'
            let result = ''

            for (let i = 0; i < length; i++) {
                result += randomChars.charAt(Math.floor(Math.random() * randomChars.length))
            }

            return result
        },

        imageUrl(path) {
            return process.env.MIX_APP_URL + '/' + path
        },

        ucfirst(string) {
            return string.charAt(0).toUpperCase() + string.slice(1)
        },

        locale() {
            return document.documentElement.lang
        },

        readableTimestamp(timestamp) {
            return moment(timestamp).format('YYYY-MM-DD HH:mm:ss')
        },

        moment(timestamp) {
            return moment(timestamp)
        },

        date(timestamp) {
            return moment(timestamp).format('YYYY-MM-DD')
        },

        dateForHumans(timestamp) {
            return moment(timestamp).fromNow()
        }
    }
}
