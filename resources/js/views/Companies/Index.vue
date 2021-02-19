<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Companies') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <table-section :count="companies.length" :headers="tableHeaders" :has-table-actions="true">
                    <template #actions>
                        <action-link :to="{name: 'companies.create'}">
                            {{ __('Create New Company') }}
                        </action-link>
                    </template>

                    <template #body>
                        <tr v-for="company in companies">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10" v-if="company.logo">
                                        <img class="h-10 w-10 rounded-full" :src="imageUrl(company.logo)" :alt="company.name">
                                    </div>

                                    <div class="ml-4 text-sm font-medium text-gray-900">
                                        {{ company.name }}
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ date(company.created_at) }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <router-link :to="{name: 'companies.edit', params: {company: company.id}}" class="text-indigo-600 hover:text-indigo-900">
                                    {{ __('Edit') }}
                                </router-link>

                                <button class="ml-6 text-red-600 hover:text-red-900 focus:outline-none" @click="confirmCompanyDeletion(company)">
                                    {{ __('Delete') }}
                                </button>
                            </td>
                        </tr>
                    </template>

                    <template #empty>
                        {{ __('You don\'t have any companies.') }}
                    </template>

                    <dialog-modal :show="confirmingCompanyDeletion" @close="closeModal">
                        <template #title>
                            {{ __('Delete Company') }}
                        </template>

                        <template #content>
                            {{ __('Are you sure you want to delete this company?') }}
                        </template>

                        <template #footer>
                            <secondary-button @click.native="closeModal">
                                {{ __('Close') }}
                            </secondary-button>

                            <danger-button class="ml-2" @click.native="deleteCompany" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                {{ __('Delete Company') }}
                            </danger-button>
                        </template>
                    </dialog-modal>
                </table-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ActionLink from '../../components/ActionLink'
import DangerButton from '../../components/DangerButton'
import DialogModal from '../../components/DialogModal'
import SecondaryButton from '../../components/SecondaryButton'
import TableSection from '../../components/TableSection'
import AppLayout from '../../layouts/AppLayout'

export default {
    components: {
        ActionLink,
        DangerButton,
        DialogModal,
        SecondaryButton,
        TableSection,
        AppLayout
    },

    data() {
        return {
            companies: [],

            tableHeaders: [
                'Name',
                'Created At'
            ],

            currentCompany: null,
            confirmingCompanyDeletion: false,
            form: this.$form.create({})
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

        confirmCompanyDeletion(company) {
            this.currentCompany = company
            this.confirmingCompanyDeletion = true
        },

        deleteCompany() {
            this.form.delete(`/api/companies/${this.currentCompany.id}`, {
                onSuccess: () => {
                    this.getCompanies()
                    this.closeModal()
                }
            })
        },

        closeModal() {
            this.confirmingCompanyDeletion = false
        }
    }
}
</script>
