import HasVueComponent from './HasVueComponent'
import EmitsEvents from './EmitsEvents'
import HasId from './HasId'
import HasClasses from './HasClasses'
import HasStyles from './HasStyles'
import HasData from './HasData'
import RunsInteractions from './RunsInteractions'

export default {
    mixins: [ HasVueComponent, EmitsEvents, HasId, HasClasses, HasStyles, HasData, RunsInteractions ],
    props: {
        vkompo: { type: Object, required: true }
    },
    data(){
        return{
            component: {},
            state: {},
            elementStore: {}
        }
    },
	computed: {

        $_loading(){ return this.$_state('loading') },
        $_hasError(){ return this.$_state('hasError') },
        $_isSuccess(){ return this.$_state('isSuccess') },
        $_hidden(){ return this.$_state('vlHidden') },
        $_displayNone(){ return this.$_data('displayNone') },

        $_kompoId() { return this.$_data('kompoId') },

        $_icon() { return this.$_data('icon') },
        $_pureLabel() { return this.component.label },
        $_label() { 
            return (this.$_icon ? '<i class="'+this.$_icon+'"></i> ' : '') + this.$_pureLabel 
        },

        $_defaultElementAttributes() {

            return Object.assign(
                this.$_data('attrs') || {}
            )
        },
        
        $_togglesId(){ return this.$_data('togglesId') }

    },
    methods: {

        $_getFromStore(key){
            return key ? this.elementStore[key] : this.elementStore
        },
        $_state(state){
            if(_.isString(state)){
                return _.get(this.component, 'state.' + state)
            }else{
                this.state = Object.assign(this.state, state)
                this.component = Object.assign({}, this.component, { state: this.state })
            }
        },
        $_toggle(toggleId){
            if(this.$_elementId() == toggleId)
                this.$_state({ vlHidden: this.$_state('vlHidden') ? false : true })
        },
        $_deliverJsonTo(componentId, json){
            if(this.$_elKompoId == componentId)
                this.elementStore = json
        },
        //do nothing because fields/trigger functions
        $_fillRecursive(formData) {}, 
        $_validate(errors) {}, 
        $_getErrors(errors) {},
        $_resetSort(exceptId) {}
    },
    created(){
        this.vkompo.$_data = this.$_data
        this.vkompo.$_state = this.$_state
        this.vkompo.$_validate = this.$_validate
        this.vkompo.$_getErrors = this.$_getErrors
        this.vkompo.$_resetSort = this.$_resetSort
        this.vkompo.$_fillRecursive = this.$_fillRecursive
        this.vkompo.$_toggle = this.$_toggle
        this.vkompo.$_deliverJsonTo = this.$_deliverJsonTo
        this.component = this.vkompo
    }
}