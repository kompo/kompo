export default {
    computed: {
        $_emptyValue() { return this.$_data('emptyValue') },
        keyLabel(){ return this.$_data('keyLabel')},
    },
    methods: {

        placeholder(index){ return (this.$_isFocused || index > 0) ? this.$_placeholder : '' },

        addNewRow(index) {
            this.component.value.splice( index + 1, 0, _.cloneDeep(this.$_emptyValue[0]))
        },
        
        $_fill(jsonFormData){
            if(this.$_pristine){
                jsonFormData[this.$_name] = ''
            }else{
                this.$_value.forEach( (value, i) => {
                    var name = this.$_name + '['+i+']'
                    this.fillValue(jsonFormData, name, value)
                })
            }
        },

        $_validate(errors) {
            this.$_setError(errors[this.$_name])
            this.$_value.forEach( (v,k) => {
                var error = this.getError(errors, k)
                if(error)
                    this.$_setError(error) //showing the last error only
            })
        },

        deleteRow(index){
            if(this.$_value.length > 1){
                this.component.value.splice( index, 1)
            }else{
                this.component.value = [_.cloneDeep(this.$_emptyValue[0])]
            }
        }
    }
}