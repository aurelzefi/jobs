<template>
    <div>
        <div class="mt-5 md:mt-0">
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 border-b border-gray-200 text-right sm:px-6" v-if="hasActions">
                    <slot name="actions"></slot>
                </div>

                <div>
                    <table class="min-w-full divide-y divide-gray-200" v-if="count">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" v-for="header in headers">
                                    {{ __(header) }}
                                </th>

                                <th scope="col" class="relative px-6 py-3" v-if="hasTableActions">
                                    <span class="sr-only">{{ __('Edit') }}</span>
                                    <span class="sr-only">{{ __('Delete') }}</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            <slot name="body"></slot>
                        </tbody>
                    </table>

                    <div v-else>
                        <div class="p-6 bg-white border-b border-gray-200">
                            <slot name="empty"></slot>
                        </div>
                    </div>

                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        count: {
            default: 0,
        },
        headers: {
            default: []
        },
        hasTableActions: {
            default: false
        }
    },

    computed: {
        hasActions() {
            return !! this.$slots.actions
        },
    }
}
</script>
