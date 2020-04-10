import Action from '../Action'

export default {

    computed: {
        //hack to make debounce work... need to write my own debounce function in $_runTrigger
        submitOnInputDebounce(){
            var trig = _.find(this.vcomponent.interactions['input'], (t) => t.action == 'submitForm')
            return trig ? trig.debounce : 0
        },
        filterOnInputDebounce(){
            var trig = _.find(this.vcomponent.interactions['input'], (t) => t.action == 'refreshCatalog')
            return trig ? trig.debounce : 0
        },
        debouncedSubmitOnInput(){ return _.debounce(this.submitOnInput, this.submitOnInputDebounce) },
        debouncedFilterOnInput(){ return _.debounce(this.filterOnInput, this.filterOnInputDebounce) }
    },

    methods: {
        //hack continued... this had to be a method...
        submitOnInput(){ this.$_runOwnInteractionsWithAction('input', 'submitForm') },
        filterOnInput(){ this.$_runOwnInteractionsWithAction('input', 'refreshCatalog') },

        $_interactionsOfType(type){
            return _.filter(this.vcomponent.interactions, (i) => {
                return i.interactionType == type
            })
        },
        $_runOwnInteractions(type){
            var interactions = this.vcomponent.interactions

            if(interactions && interactions.length)
                interactions.forEach( interaction => {
                    if(interaction.interactionType == type)
                        this.$_runAction(interaction.action) 
                })

        },
        $_runOwnInteractionsWithAction(type, action){
            var interactions = this.vcomponent.interactions
            if(interactions && interactions.length)
                interactions.forEach( interaction => {
                    if(interaction.interactionType == type)
                        if(interaction.action.actionType == action)
                            this.$_runAction(interaction.action) 
                })
            
        },
        $_runOwnInteractionsWithoutActions(type, actions){
            var interactions = this.vcomponent.interactions
            if(interactions && interactions.length)
                interactions.forEach( interaction => {
                    if(interaction.interactionType == type && !actions.includes(interaction.action.actionType))
                        this.$_runAction(interaction.action) 
                })
        },
        $_runAllActionsIn(interactions, response, parentAction){
            if(interactions && interactions.length)
                interactions.forEach( interaction => this.$_runAction(interaction.action, response, parentAction) )
        },
        $_runAction(actionSpecs, response, parentAction){
            var action = new Action(actionSpecs, this)
            action.run(response, parentAction)
        }
    }
}
