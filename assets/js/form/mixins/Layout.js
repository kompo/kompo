import Komponent from './Komponent'
import Element from '../../element/mixins/Element'

export default {
    mixins: [ Komponent ],
    data: () => ({
        komponents: []
    }),

    computed: {
        $_customLayoutAttributes(){
            return {}
        },
        $_layoutWrapperAttributes(){
            return Object.assign({
                    ...this.$_defaultElementAttributes,
                    class: this.$_classes,
                    style: this.$_elementStyles
                }, 
                this.$_customLayoutAttributes
            )
        },
        $_noMargins(){ return this.$_data('noMargins') || this.component.noMargins }, //Form takes a property (more user-friendly)
    },

	methods: {
        $_vlMargins(component) {
            return this.$_noMargins || ['Rows', 'Columns'].includes(component.component) ? '' : 'vlMargins'
        },
        $_attributes(component) { return this.$_defaultLayoutAttributes(component) },
        $_defaultLayoutAttributes(component) {
            return {
                key: component.id,
                is: this.$_vueTag(component),
                vkompo: component,
                kompoid: this.kompoid || this.$_elKompoId //this.$_elKompoId is for FormInner or FormPanel outside a form,
            }
        },
        $_getPathById(id, path){
            path = path || ''
            var result = Komponent.methods.$_getPathById.call(this, id, path)
            if(result) return result
            for(const [key,item] of this.komponents.entries()){
                result = item.$_getPathById(id, path + '.komponents[' + key + ']')
                if(result) return result
            }            
        },
        $_state(state){
            if(_.isString(state)){
                return Element.methods.$_state.call(this, state)
            }else{
                Element.methods.$_state.call(this, state)
                this.komponents.forEach( item => { item.$_state(state) })
            }
        },
        $_toggle(toggleId){
            Element.methods.$_toggle.call(this, toggleId)
            if(!this.$_state('disabled'))
                this.komponents.forEach( item => item.$_toggle(toggleId) )
        },
        $_fillRecursive(jsonFormData){
            if(!this.$_hidden)
                this.komponents.forEach( item => item.$_fillRecursive(jsonFormData) )
        },
        $_validate(errors) {
            this.komponents.forEach( item => item.$_validate(errors) )
        },
        $_getErrors(errors) {
            this.komponents.forEach( item => item.$_getErrors(errors) )
        },
        $_resetSort(exceptId) {
            this.komponents.forEach( item => item.$_resetSort(exceptId) )
        },
        $_deliverJsonTo(componentId, json){
            this.komponents.forEach( item => item.$_deliverJsonTo(componentId, json) )
        },
	},
    created() {
        this.komponents = this.component.komponents
    }

}
