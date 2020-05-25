import Field from '../mixins/Field'
import SetInitialValueAsArray from '../mixins/SetInitialValueAsArray'

export default {
    mixins: [Field, SetInitialValueAsArray],
    computed: {
        $_attributes() {
            return {
                ...this.$_defaultFieldAttributes,
                multiple: this.$_multiple || false,
            }
        },
        $_events(){
            return {
                ...this.$_defaultFieldEvents,
                change: this.addFile,
                // blur: ()=>{} //do nothing //<- why??? I commented out
            }
        },
        $_pristine() {
            return this.$_value.length == 0
        },
        $_getFiles(){
            //return Array.from(this.$refs.input.files)
            //Does not work because computed caches the refs....
        }
    },
    methods: {
        $_makeFileImages(){
            Array.from(this.$refs.input.files).forEach( file => {
                let reader = new FileReader()
                reader.readAsDataURL(new File([file], file.name, {type: file.type}))
                reader.onload = () => { 
                    file.src = reader.result
                    this.$_addFileToValue( file )
                }
            })
        },
        $_addRefFiles(){
            Array.from(this.$refs.input.files).forEach( file => {
                this.$_addFileToValue( file )
            })
        },
        $_addFileToValue(file){
            this.$_multiple ? this.component.value.push(file) : this.component.value = [file]
        },
        $_fill(jsonFormData) {
            this.$_value.forEach( (file, i) => {
                var name = this.$_name + (this.$_multiple ? '['+i+']' : '')
                
                if(file.src)
                    delete file.src
                
                if(file.id){
                    jsonFormData[name]= JSON.stringify(file)
                }else{
                    jsonFormData[name]= file 
                }            
            })
        },
        remove(index) {
            this.component.value.splice( index, 1)
            this.$_blurAction()
            this.$_changeAction()
        }
    },
}