<template>
    <select class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" :value="value" @input="$emit('input', $event.target.value)">
        <option value="">{{ defaultOption }}</option>

        <option :value="key" v-for="(value, key) in options">
            {{ value }}
        </option>
    </select>
</template>

<script>
export default {
    props: ['options', 'defaultOption', 'value'],
}
</script>

