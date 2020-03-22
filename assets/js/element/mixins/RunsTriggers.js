import Trigger from '../Trigger'

export default {

    computed: {
        //hack to make debounce work... need to write my own debounce function in $_runTrigger
        submitOnInputDebounce(){
            var trig = _.find(this.vcomponent.triggers['input'], (t) => t.action == 'submitForm')
            return trig ? trig.debounce : 0
        },
        filterOnInputDebounce(){
            var trig = _.find(this.vcomponent.triggers['input'], (t) => t.action == 'refreshCatalog')
            return trig ? trig.debounce : 0
        },
        debouncedSubmitOnInput(){ return _.debounce(this.submitOnInput, this.submitOnInputDebounce) },
        debouncedFilterOnInput(){ return _.debounce(this.filterOnInput, this.filterOnInputDebounce) }
    },

    methods: {
        //hack continued... this had to be a method...
        submitOnInput(){ this.$_runOwnTriggersWithAction('input', 'submitForm') },
        filterOnInput(){ this.$_runOwnTriggersWithAction('input', 'refreshCatalog') },

        $_triggersOfType(type){
            return this.vcomponent.triggers[type]
        },
        $_runOwnTriggers(type){
            this.$_runTriggers(this.vcomponent.triggers[type])
        },
        $_runOwnTriggersWithAction(type, action){
            this.$_runTriggersWithAction(this.vcomponent.triggers[type], action)
        },
        $_runOwnTriggersWithoutActions(type, actions){
            this.$_runTriggersWithoutActions(this.vcomponent.triggers[type], actions)
        },
        $_runTriggers(triggers, response, parentTrigger){
            if(triggers && triggers.length)
                triggers.forEach( trigger => this.$_runTrigger(trigger, response, parentTrigger) )
        },
        $_runTriggersWithAction(triggers, action){
            if(triggers && triggers.length)
                triggers.forEach( trigger => {
                    if(trigger.action == action)
                        this.$_runTrigger(trigger) 
                })
        },
        $_runTriggersWithoutActions(triggers, actions){
            if(triggers && triggers.length)
                triggers.forEach( trigger => {
                    if(!actions.includes(trigger.action))
                        this.$_runTrigger(trigger) 
                })
        },
        $_runTrigger(triggerSpecs, response, parentTrigger){
            var trigger = new Trigger(this, triggerSpecs)
            trigger.run(response, parentTrigger)
        }
    }
}
