<template>
    <nav role="navigation" :aria-label="__('Pagination Navigation')" class="flex items-center justify-between" v-if="hasPages">
        <div class="flex justify-between flex-1 sm:hidden">
            <span v-if="onFirstPage" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                {{ __('&laquo; Previous') }}
            </span>

            <router-link :to="getRouteForPreviousPage()" v-else class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                {{ __('&laquo; Previous') }}
            </router-link>

            <router-link :to="getRouteForNextPage()" v-if="hasMorePages" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                {{ __('Next &raquo;') }}
            </router-link>

            <span v-else class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
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
                    <template v-for="link in paginator.links">
                        <template v-if="link.url">
                            <span aria-current="page" v-if="currentPage === link.label">
                                <span v-html="link.label" :class="{ 'rounded-l-md': isThePreviousLink(link), 'rounded-r-md': isTheNextLink(link) }" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5">

                                </span>
                            </span>

                            <router-link :to="getPageForLabel(link.label)" v-else v-html="link.label" :aria-label="__('Go to page') + ' ' + link.label" :class="{ 'rounded-l-md': isThePreviousLink(link), 'rounded-r-md': isTheNextLink(link) }" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                            </router-link>
                        </template>

                        <span aria-disabled="true" v-else>
                            <span v-html="link.label" :class="{ 'rounded-l-md': isThePreviousLink(link), 'rounded-r-md': isTheNextLink(link) }" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">
                            </span>
                        </span>
                    </template>
                    </span>
                </span>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
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
        },
    },


    mounted() {
        //
    },

    methods: {
        getRouteForPreviousPage() {
            let params = Object.assign({
                page: this.currentPage - 1
            }, this.params)

            return {
                name: 'jobs.all',
                query: params
            }
        },

        getRouteForNextPage() {
            let params = Object.assign({
                page: this.currentPage + 1
            }, this.params)

            return {
                name: 'jobs.all',
                query: params
            }
        },

        getPageForLabel(label) {
            let params = Object.assign({}, this.params)

            if (label === this.__('Next &raquo;')) {
                params.page = this.currentPage + 1
            } else if (label === this.__('&laquo; Previous')) {
                params.page = this.currentPage - 1
            } else {
                params.page = label
            }

            return {
                name: 'jobs.all',
                query: params
            }
        },

        isThePreviousLink(link) {
            return link.label === this.__('&laquo; Previous')
        },

        isTheNextLink(link) {
            return link.label === this.__('Next &raquo;')
        }
    }
}
</script>
