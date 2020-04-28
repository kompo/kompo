import Komponent from './Komponent'

export default {
    mixins: [ Komponent ],
	computed: {
        
        $_attributes() { return this.$_defaultTriggerAttributes },
        $_defaultTriggerAttributes() { 
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
            
            //this.$_runOwnInteractionsWithAction('click', 'emitFrom')
            //this.$_runOwnInteractionsWithAction('click', 'submitForm')
            //this.$_runOwnInteractionsWithAction('click', 'refreshQuery')
            //this.$_runOwnInteractionsWithAction('click', 'sortQuery')
            
            this.$_revertPanel()
            this.$_revertFormRow()

        }
    },
    mounted(){
        this.$_togglesForm()
    }

}