<template>
    <app-select :options="companies" :default-option="__('Select a company')" v-model="proxyValue" />
</template>

<script>
import _ from 'lodash'
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
            companies: []
        }
    },

    mounted() {
        this.getCompanies()
    },

    methods: {
        getCompanies() {
            this.$http.get('/api/companies')
                .then(response => {
                    this.companies = _.mapValues(_.mapKeys(response.data, company => {
                        return company.id
                    }), company => {
                        return company.name
                    })
                })
        },
    }
}
</script>
