<template>
    <div>
        <banner />

        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <router-link :to="{name: 'jobs.all'}">
                                    <application-logo class="block h-10 w-auto fill-current text-gray-600"/>
                                </router-link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <nav-link :to="{name: 'jobs.all'}" :active="$route.name.includes('jobs.all')">
                                    {{ __('All Jobs') }}
                                </nav-link>

                                <template v-if="App.user">
                                    <nav-link :to="{name: 'jobs.create'}" :active="$route.name.includes('jobs.create')">
                                        {{ __('Post a Job') }}
                                    </nav-link>

                                    <nav-link :to="{name: 'jobs.index'}" :active="['jobs.index', 'jobs.edit'].includes($route.name)">
                                        {{ __('Jobs') }}
                                    </nav-link>

                                    <nav-link :to="{name: 'alerts.index'}" :active="$route.name.includes('alerts')">
                                        {{ __('Alerts') }}
                                    </nav-link>
                                </template>
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6" v-if="App.user">
                            <dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                        <div class="whitespace-nowrap">{{ App.user.name }}</div>

                                        <div class="ml-1">
                                            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path clip-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" fill-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </button>
                                </template>

                                <template #content>
                                    <dropdown-link :to="{name: 'user.profile'}">
                                        {{ __('Profile') }}
                                    </dropdown-link>

                                    <dropdown-link :to="{name: 'companies.index'}">
                                        {{ __('Companies') }}
                                    </dropdown-link>

                                    <dropdown-link :to="{name: 'orders.index'}">
                                        {{ __('Orders') }}
                                    </dropdown-link>

                                    <hr>

                                    <!-- Authentication -->
                                    <form :action="logoutLink" method="POST">
                                        <input name="_token" type="hidden" :value="csrfToken">

                                        <dropdown-link as="button"
                                                       onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Logout') }}
                                        </dropdown-link>
                                    </form>
                                </template>
                            </dropdown>
                        </div>

                        <div class="flex" v-else>
                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <a :href="loginLink" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    {{ __('Login') }}
                                </a>

                                <a :href="registerLink" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    {{ __('Register') }}
                                </a>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" @click="open = ! open">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': open, 'hidden': ! open}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <responsive-nav-link :to="{name: 'jobs.all'}" :active="$route.name.includes('jobs.all')">
                            {{ __('All Jobs') }}
                        </responsive-nav-link>

                        <template v-if="App.user">
                            <responsive-nav-link :to="{name: 'jobs.create'}" :active="$route.name.includes('jobs.create')">
                                {{ __('Post a Job') }}
                            </responsive-nav-link>

                            <responsive-nav-link :to="{name: 'jobs.index'}" :active="['jobs.index', 'jobs.edit'].includes($route.name)">
                                {{ __('Jobs') }}
                            </responsive-nav-link>

                            <responsive-nav-link :to="{name: 'alerts.index'}" :active="$route.name.includes('alerts')">
                                {{ __('Alerts') }}
                            </responsive-nav-link>
                        </template>

                        <template v-else>
                            <a :href="loginLink" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                                {{ __('Login') }}
                            </a>

                            <a :href="registerLink" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                                {{ __('Register') }}
                            </a>
                        </template>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200" v-if="App.user">
                        <div class="flex items-center px-4">
                            <div class="flex-shrink-0">
                                <svg class="h-10 w-10 fill-current text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                            </div>

                            <div class="ml-3">
                                <div class="font-medium text-base text-gray-800">
                                    {{ App.user.name }}
                                </div>

                                <div class="font-medium text-sm text-gray-500">
                                    {{ App.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <responsive-nav-link :to="{name: 'user.profile'}" :active="$route.name === 'user.profile'">
                                {{ __('Profile') }}
                            </responsive-nav-link>

                            <responsive-nav-link :to="{name: 'companies.index'}" :active="$route.name.includes('companies')">
                                {{ __('Companies') }}
                            </responsive-nav-link>

                            <responsive-nav-link :to="{name: 'orders.index'}" :active="$route.name.includes('orders')">
                                {{ __('Orders') }}
                            </responsive-nav-link>

                            <!-- Authentication -->
                            <form :action="logoutLink" method="POST">
                                <input name="_token" type="hidden" :value="csrfToken">

                                <responsive-nav-link as="button" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Logout') }}
                                </responsive-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"></slot>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>

            <!-- Modal Portal -->
            <portal-target name="modal" multiple></portal-target>
        </div>
    </div>
</template>

<script>
import ApplicationLogo from './../components/ApplicationLogo'
import Banner from '../components/Banner'
import Dropdown from './../components/Dropdown'
import DropdownLink from './../components/DropdownLink'
import NavLink from './../components/NavLink'
import ResponsiveNavLink from './../components/ResponsiveNavLink'

export default {
    components: {
        ApplicationLogo,
        Banner,
        Dropdown,
        DropdownLink,
        NavLink,
        ResponsiveNavLink
    },

    data() {
        return {
            open: false
        }
    },

    computed: {
        csrfToken() {
            return document.head.querySelector('meta[name="csrf-token"]').content
        },

        loginLink() {
            return `/${document.documentElement.lang}/login`
        },

        registerLink() {
            return `/${document.documentElement.lang}/register`
        },

        logoutLink() {
            return `/${document.documentElement.lang}/logout`
        }
    }
}
</script>
