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
                    <edit-profile-information-form :user="App.user"/>

                    <section-border />
                </div>

                <div>
                    <edit-password-form/>

                    <section-border />
                </div>

                <div>
                    <edit-language-form :user="App.user" :locales="App.locales"/>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import SectionBorder from '../../components/SectionBorder'
import AppLayout from '../../layouts/AppLayout'
import EditLanguageForm from './EditLanguageForm'
import EditPasswordForm from './EditPasswordForm'
import EditProfileInformationForm from './EditProfileInformationForm'

export default {
    components: {
        SectionBorder,
        AppLayout,
        EditLanguageForm,
        EditPasswordForm,
        EditProfileInformationForm
    }
}
</script>
