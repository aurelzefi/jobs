<template>
    <form-section @submitted="updateProfileInformation">
        <template #title>
            {{ __('Profile Information') }}
        </template>

        <template #description>
            {{ __('Update your account\'s profile information and email address.') }}
        </template>

        <template #form>
            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <app-label for="name">{{ __('Name') }}</app-label>
                <app-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                <app-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <app-label for="email">{{ __('Email') }}</app-label>
                <app-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                <app-input-error :message="form.errors.email" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <app-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </app-button>
        </template>
    </form-section>
</template>

<script>
import ActionMessage from '../../components/ActionMessage'
import AppButton from '../../components/Button'
import FormSection from '../../components/FormSection'
import AppInput from '../../components/Input'
import AppInputError from '../../components/InputError'
import AppLabel from '../../components/Label'

export default {
    components: {
        ActionMessage,
        AppButton,
        FormSection,
        AppInput,
        AppInputError,
        AppLabel
    },

    props: ['user'],

    data() {
        return {
            form: {
                name: this.user.name,
                email: this.user.email,
                errors: {},
                processing: false,
                successful: false
            }
        }
    },

    methods: {
        updateProfileInformation() {
            this.form.processing = true

            this.$http.put('/api/user/profile', this.form)
                .then(response => {
                    this.form.successful = true
                    this.form.processing = false

                    this.App.user = response.data
                })
                .catch(error => {
                    this.form.processing = false
                    this.form.successful = false
                    this.form.errors = error.response.data.errors
                })
        }
    }
}
</script>
