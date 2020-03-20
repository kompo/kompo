export default {
    methods: {
        $_setInitialValue(){
            this.component.value = !this.component.value ? [] : 
                (!_.isArray(this.component.value) ? [this.component.value] : this.component.value)
        }
    },
}