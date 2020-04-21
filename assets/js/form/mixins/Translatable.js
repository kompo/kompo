export default {
    data(){
        return {
            activeLocale: this.vkompo.data.locales[0],
            currentTranslation: ''
        }
    },
    computed: {
        $_attributes(){
            return {
                ...this.$_defaultFieldAttributes,
                rows: this.$_data('rows') || 3
            }
        },
        locales(){
            return this.$_data('locales')
        },
        currentLocale(){
            return this.$_data('currentLocale')
        },
        $_pristine() {
            return !this.currentTranslation
        },
    },
    methods: {
        $_inputAction(){
            this.component.value[this.activeLocale] = this.currentTranslation
            this.$_changeAction()
            this.$emit('change', this.$_value)
        },
        changeTab(locale) {
            this.activeLocale = locale
            this.currentTranslation = this.$_value[this.activeLocale]
            this.$nextTick(() => {
                if (this.component.trix) {
                    this.$refs.content.update()
                //} else if (this.$_vueComponent() == 'TranslatableEditor'){
                    //this.$refs.content.instance.editing.view.focus()
                } else {
                    this.$refs.content.focus ? 
                        this.$refs.content.focus():
                        this.$refs.content.instance.editing.view.focus()         
                }
            })
        },
        
        $_fill(jsonFormData){
            Object.keys(this.$_value).forEach(key => {
                jsonFormData[this.$_name+'['+key+']'] = this.$_value[key]
            })
        }
    },
    created(){
        this.activeLocale = this.currentLocale
        this.currentTranslation = this.$_value[this.activeLocale]
    }
}