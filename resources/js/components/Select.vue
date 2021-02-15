<template>
    <select v-model="proxySelected" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
        <option value="">{{ defaultOption }}</option>

        <option :value="key" v-for="(value, key) in options">
            {{ value }}
        </option>
    </select>
</template>

<script>
export default {
    model: {
        prop: 'selected',
        event: 'change',
    },

    props: {
        options: {
            type: Object
        },
        defaultOption: {
            type: String,
            default: ''
        },
        selected: {
            type: [String, Number],
            default: ''
        }
    },

    computed: {
        proxySelected: {
            get() {
                return this.selected;
            },
            set(val) {
                this.$emit('change', val);
            }
        }
    }
}
</script>

