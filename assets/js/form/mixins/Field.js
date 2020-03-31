import Component from './Component'

export default {
    mixins: [ Component ],

    data(){
        return {
            errors: null
        }
    },

    computed: {

        $_name() { return this.$_data('filterKey') || this.component.name },
        $_placeholder() { return this.component.placeholder },

        $_readOnly(){ return this.$_data('readOnly') },
        $_noAutocomplete(){ return this.$_data('noAutocomplete') },
        $_doesNotFill(){ return this.$_data('doesNotFill') },

        $_emptyValue() { return '' },

        $_value() { return this.component.value },
        $_getJsonValue(){
            var fieldValue = {}
            this.$_fill(fieldValue)
            return fieldValue
        },

        $_sortValue(){ return this.$_getJsonValue[this.$_name] },

        $_pristine() { return !this.$_value },
        $_isFocused(){ return this.$_state('focusedField') },
        $_multiple() { return this.component.multiple },
        
        $_attributes() { return this.$_defaultFieldAttributes },
        $_defaultFieldAttributes() { return {
            ...this.$_defaultElementAttributes,
            name: this.$_name,
            placeholder: this.$_placeholder,
            style: this.$_data('inputStyle') || '',
            class: this.$_data('inputClass') || '',
            readonly: this.$_readOnly,
            autocomplete: this.$_noAutocomplete ? 'off' : ''
        }},
        $_events() { return this.$_defaultFieldEvents },
        $_defaultFieldEvents() { return {
            focus: this.$_focusAction,
            blur: this.$_blurAction,
            keyup: this.$_keyupAction,
            change: this.$_changeAction,
            input: this.$_inputAction
        }},
        $_wrapperAttributes(){ return {
            component: this.component,
            errors: this.errors,
            class: this.$_defaultCssClass()
        }}
    },
    methods: {
        $_fillRecursive(jsonFormData){
            if(!this.$_hidden && !this.$_doesNotFill)
                this.$_fill(jsonFormData)
        },
        $_fill(jsonFormData){
            jsonFormData[this.$_name] = this.$_value || ''
        },
        $_validate(errors) {
            this.$_setError(errors[this.$_name])
            if(this.$_multiple)
                this.$_value.forEach( (v,k) => {
                    if(errors[this.$_name+'.'+k])
                        this.$_setError(errors[this.$_name+'.'+k]) //showing the last error only
                })
        },
        $_keyUp(key){}, //to be overriden in components when needed
        $_keyupAction(key){
            this.$_keyUp(key)
            this.$_clearErrors()

            this.$_runOwnInteractions('keyup')
            if(key.code === 'Enter'){
                if(this.$_data('noSubmitOnEnter')){
                    this.$_runOwnInteractionsWithoutActions('enter', ['submitForm'])
                }else{
                    this.$_runOwnInteractions('enter')
                }
            }
        },
        $_changeAction(){
            this.$kompo.vlUpdateErrorState(this.kompoid)
            
            this.$_runOwnInteractionsWithAction('change', 'emitFrom')

            if(!this.$_pristine)
                this.$_runOwnInteractionsWithAction('change', 'axiosRequest')
            
            this.$_runOwnInteractionsWithAction('change', 'submitForm')
            this.$_runOwnInteractionsWithAction('change', 'refreshCatalog')
            this.$_runOwnInteractionsWithAction('change', 'sortCatalog')

            this.$_clearErrors()
        },
        $_inputAction(){
            this.debouncedSubmitOnInput()
            this.debouncedFilterOnInput()
        },
        $_focusAction(){
            if(this.$_readOnly)
                return
            
            this.$_updateFieldState(true)
        },
        $_blurAction(){
            this.$_updateFieldState()
            this.$_runOwnInteractions('blur')
        },
        $_blurActionDelayed(delay){
            setTimeout(()=> this.$_blurAction(), delay || 200)            
        },
        $_updateFieldState(focus = false){
            this.$nextTick( () => {
                this.$_state({ 
                    focusedField: focus,
                    dirtyField: !this.$_pristine
                })
            })
        },
        $_setInitialValue(){
            if(this.$_emptyValue && this.$_pristine)
                this.$_resetValue()
        },
        $_resetValue(){
            this.component.value = _.cloneDeep(this.$_emptyValue)
        },
        $_setError(error){
            this.errors = error || null
        },
        $_getErrors(errors) {
            if(this.errors)
                errors.push(this.errors)
        },
        $_clearErrors(){
            if(this.errors)
                this.errors = null
        },
        $_resetSortValue(){
            this.$_resetValue()
        },
    },
    mounted(){
        this.$_updateFieldState()
        if(this.$_pristine)
            this.$_togglesForm()
        

        this.$_runOwnInteractionsWithAction('load', 'axiosRequest')
    },
    created() {
        this.$_setInitialValue()
        this.vcomponent.$_setError = this.$_setError
    }
}
