import axios from 'axios'
import Form from './Form'

export default class FormCreator {
    constructor(http, data) {
        this.form = new Form(axios.create(), data)
    }

    static create(data) {
        return new this(axios.create(), data).form
    }
}
