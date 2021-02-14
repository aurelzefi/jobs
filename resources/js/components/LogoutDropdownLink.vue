<template>
    <form :action="logoutLink" method="POST">
        <input :value="csrfToken" name="_token" type="hidden">

        <a class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
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
