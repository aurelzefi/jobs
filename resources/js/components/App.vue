<template>
    <div>
        <router-view></router-view>

        <!-- Progress Bar -->
        <vue-progress-bar></vue-progress-bar>
    </div>
</template>

<script>
export default {
    mounted() {
        this.$Progress.start()

        this.$router.beforeEach((to, from, next) => {
            this.$Progress.start()

            next()
        })

        this.$router.afterEach((to, from) => {
            this.$Progress.finish()
        })
    }
}
</script>
