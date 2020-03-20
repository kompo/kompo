import Element from './Element'

export default {
    mixins: [ Element ],
    props: {
    	vuravelid: { type: String, required: false }
    },
    methods: {
        click(){
            this.$emit('click')

            if(!this.vcomponent)
                return

            this.$_runOwnTriggersWithoutActions('click', ['submitForm', 'sortCatalog', 'emitFrom'])

        }
    }
}
