<template>
    <select v-model="proxyValue" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
        <option value="">{{ defaultOption }}</option>

        <option :value="key" v-for="(value, key) in options">
            {{ __(value) }}
        </option>
    </select>
</template>

<script>
export default {
    model: {
        prop: 'value',
        event: 'change',
    },

    props: {
        options: {
            default: {}
        },
        defaultOption: {
            default: ''
        },
        value: {
            default: ''
        }
    },

    computed: {
        proxyValue: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit('change', val);
            }
        }
    }
}
</script>

