<template>
    <app-select :options="countries" :default-option="__('Select a country')" v-model="proxyValue" />
</template>

<script>
import AppSelect from '../components/Select'

export default {
    model: {
        prop: 'value',
        event: 'change',
    },

    props: {
        value: {
            default: ''
        }
    },

    components: {AppSelect},

    computed: {
        proxyValue: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit('change', val);
            }
        }
    },

    data() {
        return {
            countries: {}
        }
    },

    mounted() {
        this.getCountries()
    },

    methods: {
        getCountries() {
            this.$http.get('/api/countries')
                .then(response => {
                    this.countries = this.mapValues(response.data, country => country.name)
                })
        },
    }
}
</script>
