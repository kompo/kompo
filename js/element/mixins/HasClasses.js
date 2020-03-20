export default {
    computed: {
        $_phpClasses() { return this.component.class || '' },
        $_customClassArray() { return [] }, //to be overwritten by components
        $_classes() {
        	return this.$_classString(
                    [ this.$_defaultCssClass() ]
                        .concat(this.$_customClassArray)
                        .concat(this.$_phpClasses)
                )
        }
    },
    methods: {
        $_classString(classes){
            return classes.filter(n => n).join(' ')
        }
    }
}
