<template>
    <app-select :options="countries" :default-option="__('Select a country')" v-model="proxySelected" />
</template>

<script>
import AppSelect from '../components/Select'

export default {
    model: {
        prop: 'selected',
        event: 'change',
    },

    props: {
        selected: {
            type: [String, Number],
            default: ''
        }
    },

    components: {AppSelect},

    computed: {
        proxySelected: {
            get() {
                return this.selected;
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
