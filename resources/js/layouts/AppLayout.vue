<template>
    <div>
        <!-- Navigation Bar -->
        <navigation></navigation>

        <!-- Page Heading -->
        <app-header>
            <slot name="header"></slot>
        </app-header>

        <!-- Page Content -->
        <main>
            <slot></slot>
        </main>
    </div>
</template>

<script>
import AppHeader from './../components/Header'
import Navigation from './../components/Navigation'

export default {
    components: {AppHeader, Navigation}
}
</script>

<style scoped>

</style>
