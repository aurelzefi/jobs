<template>
    <div v-show="on">
        <div class="text-sm text-gray-600">
            <slot />
        </div>
    </div>
</template>

<script>
    export default {
        props: ['on'],
    }
</script>
