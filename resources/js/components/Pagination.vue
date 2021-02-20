<template>
    <nav role="navigation" :aria-label="__('Pagination Navigation')" class="flex items-center justify-between" v-if="hasPages">
        <div class="flex justify-between flex-1 sm:hidden">
            <span v-if="onFirstPage" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                {{ __('&laquo; Previous') }}
            </span>

            <button @click="getPreviousPage" v-else class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                {{ __('&laquo; Previous') }}
            </button>

            <button @click="getNextPage" v-if="hasMorePages" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                {{ __('Next &raquo;') }}
            </button>

            <span v-if="! hasMorePages" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                {{ __('Next &raquo;') }}
            </span>
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 leading-5">
                    {{ __('Showing') }}
                    <span class="font-medium">{{ firstItem }}</span>
                    {{ __('to') }}
                    <span class="font-medium">{{ lastItem }}</span>
                    {{ __('of') }}
                    <span class="font-medium">{{ total }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    <span v-if="onFirstPage" aria-disabled="true" :aria-label="__('&laquo; Previous')">
                        <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md leading-5" aria-hidden="true">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </span>

                    <button @click="getPreviousPage" v-else rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" :aria-label="__('&laquo; Previous')">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <template v-for="link in paginator.links">
                        <template v-if="link.url">
                            <span aria-current="page" v-if="currentPage === link.label">
                                <span v-html="link.label" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5">

                                </span>
                            </span>

                            <button @click="getPage(link.label)" v-else v-html="link.label" :aria-label="__('Go to page') + ' ' + link.label" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">

                            </button>
                        </template>

                        <span aria-disabled="true" v-else>
                            <span v-html="link.label" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">

                            </span>
                        </span>
                    </template>

                    <button @click="getNextPage" v-if="hasMorePages" :aria-label="__('Next &raquo;')" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <span v-else aria-disabled="true" :aria-label="__('Next &raquo;')">
                        <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md leading-5" aria-hidden="true">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </span>
                </span>
            </div>
        </div>
    </nav>
</template>

<script>
import Button from './Button'

export default {
    components: {Button},
    props: ['paginator', 'params'],

    computed: {
        hasPages() {
            return this.paginator.last_page > 1
        },

        onFirstPage() {
            return this.paginator.current_page === 1
        },

        hasMorePages() {
            return this.paginator.current_page < this.paginator.last_page
        },

        firstItem() {
            return this.paginator.from
        },

        lastItem() {
            return this.paginator.to
        },

        total() {
            return this.paginator.total
        },

        currentPage() {
            return this.paginator.current_page
        }
    },

    methods: {
        getPreviousPage() {
            this.$emit('update:page', this.currentPage--)
        },

        getNextPage() {
            this.$emit('update:page', this.currentPage++)
        },

        getPage(page) {
            this.$emit('update:page', page)
        }
    }
}
</script>
