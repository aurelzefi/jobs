<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Companies') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <action-section>
                    <template #title>
                        {{ __('Companies') }}
                    </template>

                    <template #description>
                        {{ __('Description Here') }}
                    </template>

                    <template #actions>
                        <action-link :to="{name: 'companies.create'}">
                            {{ __('Create New Company') }}
                        </action-link>
                    </template>

                    <template #content>
                        <div class="space-y-6" v-if="companies.length">
                            <div class="flex items-center justify-between" v-for="company in companies" :key="company.id">
                                <div class="flex items-center">
                                    <div v-if="company.logo">
                                        <img :src="imageUrl(company.logo)" :alt="company.name" class="rounded-full h-10 w-10 object-cover">
                                    </div>

                                    <div v-else>

                                    </div>

                                    <span class="ml-2">
                                        {{ company.name }}
                                    </span>
                                </div>

                                <div class="flex items-center">
                                    <router-link :to="{name: 'companies.edit', params: {company: company.id}}" class="cursor-pointer ml-6 text-sm text-gray-400 underline">
                                        {{ __('Edit') }}
                                    </router-link>

                                    <button class="cursor-pointer ml-6 text-sm text-red-500" @click="destroy">
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            {{ __('You have not created any alerts yet.') }}
                        </div>
                    </template>
                </action-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ActionLink from '../../components/ActionLink'
import ActionSection from '../../components/ActionSection'
import AppLayout from '../../layouts/AppLayout'

export default {
    components: {
        ActionLink,
        ActionSection,
        AppLayout
    },

    data() {
        return {
            companies: []
        }
    },

    mounted() {
        this.getCompanies()
    },

    methods: {
        getCompanies() {
            this.$http.get('/api/companies')
                .then(response => {
                    this.companies = response.data
                })
        },

        destroy() {
            //
        }
    }
}
</script>
