<template>
    <div class="min-h-screen bg-gray-100">
        <router-view></router-view>
    </div>
</template>

<script>
import Navigation from './Navigation'

export default {
    components: {Navigation}
}
</script>
