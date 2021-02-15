<template>
    <form-section @submitted="updatePassword">
        <template #title>
            {{ __('Update Password') }}
        </template>

        <template #description>
            {{ ('Ensure your account is using a long, random password to stay secure.') }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <app-label for="current_password">{{ __('Current Password') }}</app-label>
                <app-input id="current_password" type="password" class="mt-1 block w-full" v-model="form.current_password" autocomplete="current-password" />
                <app-input-error :message="form.errors.current_password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <app-label for="password">{{ ('New Password') }}</app-label>
                <app-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" autocomplete="new-password" />
                <app-input-error :message="form.errors.password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <app-label for="password_confirmation">{{ __('Confirm Password') }}</app-label>
                <app-input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" autocomplete="new-password" />
                <app-input-error :message="form.errors.password_confirmation" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <action-message :on="form.successful" class="mr-3">
                {{ __('Updated.') }}
            </action-message>

            <app-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ __('Update') }}
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

    data() {
        return {
            form: this.$form.create({
                current_password: '',
                password: '',
                password_confirmation: '',
            })
        }
    },

    methods: {
        updatePassword() {
            this.form.put('/api/user/password', {
                onSuccess: () => {
                    this.form.current_password = ''
                    this.form.password = ''
                    this.form.password_confirmation = ''
                }
            })
        }
    }
}
</script>
