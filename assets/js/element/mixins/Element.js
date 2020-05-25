import HasVueComponent from './HasVueComponent'
import EmitsEvents from './EmitsEvents'
import HasId from './HasId'
import HasClasses from './HasClasses'
import HasStyles from './HasStyles'
import HasData from './HasData'
import RunsInteractions from './RunsInteractions'

export default {
    mixins: [ HasVueComponent, EmitsEvents, HasId, HasClasses, HasStyles, HasData, RunsInteractions],
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

        $_icon() { return this.$_data('icon') },
        $_rIcon() { return this.$_data('rIcon') },
        $_pureLabel() { return this.component.label },
        $_label() { 
            return (this.$_icon ? '<i class="'+this.$_icon+'"></i> ' : '') + this.$_pureLabel 
        },

        $_defaultElementAttributes() {

            return Object.assign(
                this.$_data('attrs') || {},
                this.$_elementId() ? { id: this.$_elementId() } : {}
            )
        },
        
        $_toggleId(){ return this.$_data('toggleId') },
        $_toggleOnLoad(){ return this.$_data('toggleOnLoad') }

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
                this.$_toggleSelf()
        },
        $_toggleSelf(){
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
        $_resetSort(exceptId) {},
        $_attachEvents(){
            /*if(!this.$_hasInteractions) no cuz select does ajax without an interaction for ex.
                return */
            if(!this.$_elKompoId)
                return

            this.$_vlOn('vlDeliverFormInfo'+this.$_elKompoId, (formInfo) => { //for submit TODO: merge with below

                this.formInfo = formInfo
            })
            this.$_vlOn('vlDeliverKompoInfo'+this.$_elKompoId, (kompoInfo) => { //for any axios request

                this.kompoInfo = kompoInfo

            })
        },
        $_destroyEvents(){
            this.$_vlOff([
                'vlDeliverFormInfo'+this.$_elKompoId,
                'vlDeliverKompoInfo'+this.$_elKompoId
            ])
        }
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

        this.$_destroyEvents()
        this.$_attachEvents()
    },
    updated() {
        this.$_destroyEvents()
        this.$_attachEvents()
    },
}