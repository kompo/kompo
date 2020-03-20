import {Element} from 'vuravel-elements'

export default {
    mixins: [ Element ],
    props: {
        vuravelid: { type: String, required: false }
    },
    data(){
        return {
        }
    },
	computed: {
        
        $_submitsForm(){ return this.$_data('submitsForm') },
        $_submitsOnInput(){ return this.$_data('submitsOnInput') },
        $_hideIndicators(){ return this.$_data('hideIndicators') },
        $_sortsCatalog(){ return this.$_data('sortsCatalog') },
        $_sortValue(){ return this.$_sortsCatalog }, //overriden in Field and Th

        $_debouncedSubmit(){ return _.debounce(this.$_submit, this.$_submitsOnInput)}

    },
    methods:{
        $_getPathById(id, path){
            if(this.$_elementId(null) == id)
                return path.substring(1) //because the first . should not be taken into account
        },
        $_togglesForm(toggleId){
            if(toggleId || this.$_togglesId)
                this.$vuravel.vlToggle(this.vuravelid, toggleId || this.$_togglesId)
        },
        $_submit(){
            this.$_state({ loading: true })
            this.$vuravel.vlSubmit(this.vuravelid, this)
        },
        $_resetSort(exceptId) {
            if(this.$_sortsCatalog && this.$_elementId() != exceptId){
                this.$_state({ activeSort: false })
                this.$_resetSortValue()
            }
        },
        $_resetSortValue(){}, //overriden in Field
        $_revertPanel(){
            if(this.$_revertsPanel)
                this.$_closestParentOfType('VlFormPanel').revertPanel()
        },
        $_revertFormRow(){
            if(this.$_revertsFormRow)
                this.$_closestParentOfType('VlMultiForm').revertFormRow(this.$_elementId())
        },
        $_closestParentOfType(type){
            let vm = this.$parent
            while(vm && vm.$options._componentTag !== type) { vm = vm.$parent }
            return vm 
        },
        $_attachEvents(){
            this.$_vlOn('vlDeliverFormInfo'+this.$_elementId(), (formInfo) => {
                this.formInfo = formInfo
            })
        },
        $_destroyEvents(){
            this.$_vlOff([
                'vlDeliverFormInfo'+this.$_elementId()
            ])
        }

    },

    created() {
        this.$_destroyEvents()
        this.$_attachEvents()
    },
    updated() {
        this.$_destroyEvents()
        this.$_attachEvents()
    }
}