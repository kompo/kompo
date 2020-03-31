import Component from './Component'

export default {
    mixins: [ Component ],
	computed: {
        
        $_attributes() { return this.$_defaultOtherAttributes },
        $_defaultOtherAttributes() { 
            return {
                ...this.$_defaultElementAttributes,
                style: this.$_elementStyles,
                class: this.$_classes
            }
        },
        showSpinner(){ return this.$_loading },
        showCheckmark(){ return !this.$_loading && this.$_isSuccess },
        showError(){ return !this.$_loading && this.$_hasError }
	},
    methods:{
        $_clickAction(){
            this.$_togglesForm()
            
            this.$_runOwnInteractionsWithAction('click', 'emitFrom')
            
            this.$_runOwnInteractionsWithAction('click', 'submitForm')
            this.$_runOwnInteractionsWithAction('click', 'refreshCatalog')
            this.$_runOwnInteractionsWithAction('click', 'sortCatalog')
            
            this.$_revertPanel()
            this.$_revertFormRow()

        }
    },
    mounted(){
        this.$_togglesForm()
    }

}