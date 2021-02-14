<template>
    <div class="relative">
        <div v-show="open" class="fixed inset-0" tabindex="-1" @click="open = false"></div>

        <div @click="open = ! open">
            <slot name="trigger"></slot>
        </div>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">
            <div v-show="open"
                 :class="[widthClass, alignmentClasses]"
                 class="absolute z-50 mt-2 rounded-md shadow-lg"
                 style="display: none;"
                 @click="$emit('closed')">
                <div :class="contentClasses !== undefined ? contentClasses : 'py-1 bg-white'" class="rounded-md ring-1 ring-black ring-opacity-5">
                    <slot name="content"></slot>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: ['align', 'width', 'contentClasses'],

    data() {
        return {
            open: false
        }
    },

    computed: {
        alignmentClasses() {
            switch (this.align) {
                case 'left':
                    return 'origin-top-left left-0';
                case 'top':
                    return 'origin-top';
                case 'right':
                default:
                    return 'origin-top-right right-0';
            }
        },

        widthClass() {
            switch (this.width) {
                case '48':
                    return 'w-48';
            }
        }
    }
}
</script>

<style scoped>

</style>
