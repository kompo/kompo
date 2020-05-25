import Action from '../../core/Action'

export default {

    computed: {
        $_debounce(){ return this.$_data('debounce') || 0 },
        //hack to make debounce work... need to write my own debounce function in $_runTrigger
        debouncedSubmitOnInput(){ return _.debounce(this.submitOnInput, this.$_debounce) },
        debouncedFilterOnInput(){ return _.debounce(this.filterOnInput, this.$_debounce) },

        $_interactions(){ return this.vkompo.interactions },

        $_hasInteractions(){ return this.$_interactions && this.$_interactions.length }
    },

    methods: {
        //hack continued... this had to be a method...
        submitOnInput(){ this.$_runOwnInteractionsWithAction('input', 'submitForm') },
        filterOnInput(){ this.$_runOwnInteractionsWithAction('input', 'browseQuery') },

        $_interactionsOfType(type){
            return _.filter(this.$_interactions, (i) => {
                return i.interactionType == type
            })
        },
        $_runOwnInteractions(type, parameters){
            if(this.$_hasInteractions)
                this.$_interactions.forEach( interaction => {
                    if(interaction.interactionType == type)
                        this.$_runAction(interaction.action, parameters) 
                })

        },
        $_runOwnInteractionsWithAction(type, action){
            if(this.$_hasInteractions)
                this.$_interactions.forEach( interaction => {
                    if(interaction.interactionType == type)
                        if(interaction.action.actionType == action)
                            this.$_runAction(interaction.action) 
                })
            
        },
        $_runOwnInteractionsWithoutActions(type, actions){
            if(this.$_hasInteractions)
                this.$_interactions.forEach( interaction => {
                    if(interaction.interactionType == type && !actions.includes(interaction.action.actionType))
                        this.$_runAction(interaction.action) 
                })
        },
        $_runInteractionsOfType(parentAction, type, response){
            if(parentAction.interactions && parentAction.interactions.length)
                parentAction.interactions.forEach( interaction => {
                    if(interaction.interactionType == type)
                        this.$_runAction(interaction.action, {
                            response: response, 
                            parentAction: parentAction
                        })
                })
        },
        $_runAction(actionSpecs, parameters){
            var action = new Action(actionSpecs, this)
            action.run(parameters)
        }
    }
}
