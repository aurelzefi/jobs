<template>
    <form :action="logoutLink" method="POST">
        <input :value="csrfToken" name="_token" type="hidden">

        <a class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out"
           href="/logout" onclick="event.preventDefault(); this.closest('form').submit();">
            <slot></slot>
        </a>
    </form>
</template>

<script>
export default {
    computed: {
        csrfToken() {
            return document.head.querySelector('meta[name="csrf-token"]').content
        },

        logoutLink() {
            return `/${document.documentElement.lang}/logout`
        }
    }
}
</script>

<style scoped>

</style>
