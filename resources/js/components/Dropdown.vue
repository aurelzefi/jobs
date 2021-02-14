<template>
    <div class="relative">
        <button v-if="open" class="fixed inset-0 h-full w-full cursor-default focus:outline-none" tabindex="-1"
                @click="$emit('closed')"></button>
        <div @click="$emit('toggled')">
            <slot name="trigger"></slot>
        </div>

        <transition
            v-on:enter="transition = 'enter'"
            v-on:leave="transition = 'leave'"
            v-on:before-enter="transition = 'before-enter'"
            v-on:after-enter="transition = 'after-enter'"
            v-on:before-leave="transition = 'before-leave'"
            v-on:after-leave="transition = 'after-leave'">
            <div v-show="open"
                 :class="[widthClass, alignmentClasses, transitionClasses]"
                 class="absolute z-50 mt-2 rounded-md shadow-lg"
                 style="display: none;"
                 @click="$emit('closed')">
                <div :class="contentClasses" class="rounded-md ring-1 ring-black ring-opacity-5">
                    <slot name="content"></slot>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: ['align', 'width', 'open', 'contentClasses'],

    data() {
        return {
            transition: ''
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
        },

        transitionClasses() {
            switch (this.transition) {
                case 'before-enter':
                    return 'transition ease-out duration-200'
                case 'enter':
                    return 'transform opacity-0 scale-95'
                case 'after-enter':
                    return 'transform opacity-100 scale-100'
                case 'before-leave':
                    return 'transition ease-in duration-75'
                case 'leave':
                    return 'transform opacity-100 scale-100'
                case 'after-leave':
                    return 'transform opacity-0 scale-95'
            }
        }
    }
}
</script>

<style scoped>

</style>
