import Element from '../../element/mixins/Element'

export default {
    mixins: [ Element ],
    props: {
        kompoid: { type: String, required: false }
    },
	computed: {

        $_submitsOnInput(){ return this.$_data('submitsOnInput') },
        $_hideIndicators(){ return this.$_data('hideIndicators') },
        $_sortsQuery(){ return this.$_data('sortsQuery') },
        $_sortValue(){ return this.$_sortsQuery }, //overriden in Field and Th

        $_revertsPanel(){ return this.$_data('revertsPanel') },
        $_revertsFormRow(){ return this.$_data('revertsFormRow') },
        
        $_debouncedSubmit(){ return _.debounce(this.$_submit, this.$_submitsOnInput)}

    },
    methods:{
        $_getPathById(id, path){
            if(this.$_elKompoId == id)
                return path.substring(1) //because the first . should not be taken into account
        },
        $_togglesForm(toggleId){
            if(toggleId || this.$_togglesId)
                this.$kompo.vlToggle(this.kompoid, toggleId || this.$_togglesId)
        },
        $_submit(){
            this.$_state({ loading: true })
            this.$kompo.vlSubmit(this.kompoid, this)
        },
        $_resetSort(exceptId) {
            if(this.$_sortsQuery && this.$_elKompoId != exceptId){
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
                this.$_closestParentOfType('VlMultiForm').revertFormRow(this.$_elKompoId)
        },
        $_closestParentOfType(type){
            let vm = this.$parent
            while(vm && vm.$options._componentTag !== type) { vm = vm.$parent }
            return vm 
        },
        $_attachEvents(){
            this.$_vlOn('vlDeliverFormInfo'+this.$_elKompoId, (formInfo) => {
                this.formInfo = formInfo
            })
        },
        $_destroyEvents(){
            this.$_vlOff([
                'vlDeliverFormInfo'+this.$_elKompoId
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