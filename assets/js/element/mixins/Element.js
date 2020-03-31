import EmitsEvents from './EmitsEvents'
import HasId from './HasId'
import HasClasses from './HasClasses'
import HasStyles from './HasStyles'
import HasData from './HasData'
import PerformsAjax from './PerformsAjax'
import RunsInteractions from './RunsInteractions'

export default {
    mixins: [ EmitsEvents, HasId, HasClasses, HasStyles, HasData, PerformsAjax, RunsInteractions ],
    props: {
        vcomponent: { type: Object, required: true }
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
        $_kompoRoute() {return this.$_data('kompoRoute')},

        $_icon() { return this.$_data('icon') },
        $_label() { 
            return (this.$_icon ? '<i class="'+this.$_icon+'"></i> ' : '') + this.component.label 
        },

        $_defaultElementAttributes() {

            return Object.assign(
                this.$_data('attrs') || {}, 
                this.$_elementId(null) ? { id: this.$_elementId(null) } : {}
            )
        },
        $_component() { return this.component.component },
        
        $_togglesId(){ return this.$_data('togglesId') }

    },
    methods: {

        $_defaultCssClass(component) {
            return 'vl'+(component || this.$_component)
            //return 'vl' + _.upperFirst(_.camelCase(component || this.$_component)) //?? to delete
        },
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
            if(this.$_elKompoId == toggleId)
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
        this.vcomponent.$_data = this.$_data
        this.vcomponent.$_state = this.$_state
        this.vcomponent.$_validate = this.$_validate
        this.vcomponent.$_getErrors = this.$_getErrors
        this.vcomponent.$_resetSort = this.$_resetSort
        this.vcomponent.$_fillRecursive = this.$_fillRecursive
        this.vcomponent.$_toggle = this.$_toggle
        this.vcomponent.$_deliverJsonTo = this.$_deliverJsonTo
        this.component = this.vcomponent
    }
}