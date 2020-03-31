import Element from './Element'

export default {
    mixins: [ Element ],
    props: {
    	kompoid: { type: String, required: false }
    },
    methods: {
        click(){
            this.$emit('click')

            if(!this.vcomponent)
                return

            this.$_runOwnInteractionsWithoutActions('click', ['submitForm', 'sortCatalog', 'emitFrom'])

        }
    }
}
