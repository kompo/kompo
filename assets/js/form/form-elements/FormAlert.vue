<template>

    <vl-alert v-bind="$_attributes" v-show="!$_hidden" />

</template>

<script>
import Other from '../mixins/Other'

export default {
    mixins: [Other],
    computed: {
        
        $_attributes() { 
        	return {
        		...this.$_defaultOtherAttributes,
    			message: this.$_pureLabel,
    			iconclass: this.$_icon
        	}
        }
    }
}
</script>
