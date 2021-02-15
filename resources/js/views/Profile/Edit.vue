<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Profile') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div>
                    <update-profile-information-form :user="App.user"/>
                    <section-border />
                </div>

                <div>
                    <update-password-form/>

                    <section-border />
                </div>

                <div>
                    <update-language-form :user="App.user" :locales="App.locales"/>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import SectionBorder from '../../components/SectionBorder'
import AppLayout from '../../layouts/AppLayout'
import UpdateLanguageForm from './UpdateLanguageForm'
import UpdatePasswordForm from './UpdatePasswordForm'
import UpdateProfileInformationForm from './UpdateProfileInformationForm'

export default {
    components: {
        SectionBorder,
        AppLayout,
        UpdateLanguageForm,
        UpdatePasswordForm,
        UpdateProfileInformationForm
    }
}
</script>
