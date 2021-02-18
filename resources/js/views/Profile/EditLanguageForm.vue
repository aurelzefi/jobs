<template>
    <form-section @submitted="updateLocale">
        <template #title>
            {{ __('Default Language') }}
        </template>

        <template #description>
            {{ __('Manage the default language you want to use. We use when sending you emails regarding your activity.') }}
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <app-label for="locale">{{ __('Language') }}</app-label>
                <app-select id="locale" class="mt-1 block w-full" :options="locales" :default-option="__('Select a language')" v-model="form.locale" />
                <app-input-error :message="form.errors.locale" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <action-message :on="form.successful" class="mr-3">
                {{ __('Saved.') }}
            </action-message>

            <app-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ __('Save') }}
            </app-button>
        </template>
    </form-section>
</template>

<script>
import ActionMessage from '../../components/ActionMessage'
import AppButton from '../../components/Button'
import FormSection from '../../components/FormSection'
import AppInputError from '../../components/InputError'
import AppLabel from '../../components/Label'
import AppSelect from '../../components/Select'

export default {
    components: {
        ActionMessage,
        AppButton,
        FormSection,
        AppInputError,
        AppLabel,
        AppSelect
    },

    props: ['user', 'locales'],

    data() {
        return {
            form: this.$form.create({
                locale: this.user.locale,
            })
        }
    },

    methods: {
        updateLocale() {
            this.form.put('/api/user/locale')
        }
    }
}
</script>
