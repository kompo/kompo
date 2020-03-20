export default {
    computed: {
    	//by default, one id per element, otherwise concatenate key to id
        $_elementId() { return key => this.component.id + ( key || '' ) },

        $_displayIdAttribute(){ return this.$_data('displayIdAttribute') },   
    }
}
