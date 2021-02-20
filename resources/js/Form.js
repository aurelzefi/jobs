import _ from 'lodash'

export default class Form {
    constructor(http, data) {
        this.http = http
        this.errors = {}
        this.successful = false
        this.processing = false
        this.shouldBeFormData = false

        for (const key in data) {
            if (data.hasOwnProperty(key)) {
                this[key] = data[key]
            }
        }
    }

    asFormData() {
        this.shouldBeFormData = true

        return this
    }

    formatErrors(errors) {
        return _.mapValues(errors, error => error[0])
    }

    get(uri, {onSuccess, onFailure} = {}) {
        this.action('get', uri, onSuccess, onFailure)
    }

    post(uri, {onSuccess, onFailure} = {}) {
        this.action('post', uri, onSuccess, onFailure)
    }

    put(uri, {onSuccess, onFailure} = {}) {
        this.action('put', uri, onSuccess, onFailure)
    }

    delete(uri, {onSuccess, onFailure} = {}) {
        this.action('delete', uri, onSuccess, onFailure)
    }

    action(method, uri, onSuccess, onFailure) {
        this.successful = false
        this.processing = true
        this.errors = {}

        let data = method === 'get' ? {params: this.getData()} : this.getData()

        this.http[method](uri, data)
            .then(response => {
                this.successful = true
                this.processing = false
                this.errors = {}

                if (onSuccess) {
                    onSuccess(response)
                }
            })
            .catch(error => {
                this.processing = false
                this.successful = false
                this.errors = this.formatErrors(
                    error.response.data.errors
                )

                if (onFailure) {
                    onFailure(error)
                }
            })
    }

    getData() {
        const data = _.pickBy(this, (value, key) => {
            return ! _.includes(['http', 'errors', 'successful', 'processing', 'shouldBeFormData'], key)
        })

        if (! this.shouldBeFormData) {
            return data
        }

        const formData = new FormData()

        for (let key in data) {
            if (data.hasOwnProperty(key)) {
                formData.append(key, data[key])
            }
        }

        return formData
    }
}
