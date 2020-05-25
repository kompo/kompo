export default {
    computed: {
        $_defaultInputAttributes() { return {
            type: this.$_inputType || 'text',
            min: this.$_inputMin,
            max: this.$_inputMax,
            step: this.$_inputStep,
            pattern: this.$_inputPattern,
            maxlength: this.$_inputMaxlength
        }},
        $_inputType(){return this.$_data('inputType')},
        $_inputMin(){return this.$_data('inputMin')},
        $_inputMax(){return this.$_data('inpuMax')},
        $_inputStep(){return this.$_data('inputStep')},
        $_inputPattern(){return this.$_data('inputPattern')},
        $_inputMaxlength(){return this.$_data('inputMaxlength')}
    }
}