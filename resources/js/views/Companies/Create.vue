<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Create New Company') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <form-section @submitted="store">
                    <template #title>
                        {{ __('Title') }}
                    </template>

                    <template #description>
                        {{ __('Description') }}
                    </template>

                    <template #form>
                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="name">{{ __('Name') }}</app-label>
                            <app-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" ref="name" />
                            <app-input-error :message="form.errors.name" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="description">{{ __('Description') }}</app-label>
                            <app-textarea id="description" type="text" rows="6" class="mt-1 block w-full" v-model="form.description" />
                            <app-input-error :message="form.errors.description" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="address">{{ __('Address') }}</app-label>
                            <app-input id="address" type="text" class="mt-1 block w-full" v-model="form.address" />
                            <app-input-error :message="form.errors.address" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="city">{{ __('City') }}</app-label>
                            <app-input id="city" type="text" class="mt-1 block w-full" v-model="form.city" />
                            <app-input-error :message="form.errors.city" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="country">{{ __('Country') }}</app-label>
                            <app-select id="country" class="mt-1 block w-full" :options="countries" :default-option="__('Select a country')" v-model="form.country_id" />
                            <app-input-error :message="form.errors.country_id" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <app-label for="website">{{ __('Website') }}</app-label>
                            <app-input id="website" type="text" class="mt-1 block w-full" v-model="form.website" />
                            <app-input-error :message="form.errors.website" class="mt-2" />
                        </div>

                        <div class="col-span-6 sm:col-span-4">
                            <input type="file" class="hidden" ref="logo" @change="updateLogoPreview">

                            <app-label for="logo">
                                {{ __('Logo') }}
                            </app-label>

                            <div class="mt-2" v-show="logoPreview">
                                <span class="block rounded-full w-20 h-20"
                                      :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + logoPreview + '\');'">
                                </span>
                            </div>

                            <secondary-button class="mt-2 mr-2" type="button" @click.native.prevent="selectNewLogo">
                                {{ __('Select A Logo') }}
                            </secondary-button>

                            <app-input-error :message="form.errors.logo" class="mt-2" />
                        </div>
                    </template>

                    <template #actions>
                        <app-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ __('Save') }}
                        </app-button>
                    </template>
                </form-section>
            </div>
        </div>
    </app-layout>
</template>

<script>
import ActionMessage from '../../components/ActionMessage'
import AppButton from '../../components/Button'
import AppCheckbox from '../../components/Checkbox'
import FormSection from '../../components/FormSection'
import AppInput from '../../components/Input'
import AppInputError from '../../components/InputError'
import AppLabel from '../../components/Label'
import SecondaryButton from '../../components/SecondaryButton'
import SectionBorder from '../../components/SectionBorder'
import AppSelect from '../../components/Select'
import AppTextarea from '../../components/Textarea'
import AppLayout from '../../layouts/AppLayout'

export default {
    components: {
        ActionMessage,
        AppButton,
        AppCheckbox,
        FormSection,
        AppInput,
        AppInputError,
        AppLabel,
        SecondaryButton,
        SectionBorder,
        AppSelect,
        AppTextarea,
        AppLayout
    },

    data() {
        return {
            form: this.$form.create({
                country_id: '',
                name: '',
                description: '',
                website: '',
                address: '',
                city: '',
                logo: '',
            }).asFormData(),

            logoPreview: null
        }
    },

    mounted() {
        this.$refs.name.focus()

        this.getCountries()
    },

    methods: {
        store() {
            if (this.$refs.logo.files.length) {
                this.form.logo = this.$refs.logo.files[0]
            }

            this.form.post('/api/companies', {
                onSuccess: () => this.$router.push({name: 'companies.index'}),
                onFailure: () => this.$refs.name.focus()
            })
        },

        selectNewLogo() {
            this.$refs.logo.click();
        },

        updateLogoPreview() {
            const reader = new FileReader();

            reader.onload = (e) => {
                this.logoPreview = e.target.result;
            };

            reader.readAsDataURL(this.$refs.logo.files[0]);
        },
    }
}
</script>
