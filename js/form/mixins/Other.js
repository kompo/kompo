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
            
            this.$_runOwnTriggersWithAction('click', 'emitFrom')
            
            this.$_runOwnTriggersWithAction('click', 'submitForm')
            this.$_runOwnTriggersWithAction('click', 'refreshCatalog')
            this.$_runOwnTriggersWithAction('click', 'sortCatalog')
            
            this.$_revertPanel()
            this.$_revertFormRow()

        }
    },
    mounted(){
        this.$_togglesForm()
    }

}