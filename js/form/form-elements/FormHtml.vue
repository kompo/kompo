<template>

    <div 
        v-if="!$_displayNone" 
        v-show="!$_hidden" 
        v-bind="$_attributes" 
        v-html="$_label" 
        @click.stop="activate" />

</template>

<script>
import Other from '../mixins/Other'

export default {
    mixins: [Other],
    methods: {
    	activate(){
            if(!this.$_triggersOfType('click'))
                return
            
            this.$emit('activate') //for catalog activation
            this.$_clickAction()
    	}
    }
}
</script>
