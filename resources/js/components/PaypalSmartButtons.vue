<template>
    <div id="paypal-button-container"></div>
</template>

<script>
export default {
    mounted() {
        window.paypal.Buttons().render('#paypal-button-container')
    },
}
</script>
