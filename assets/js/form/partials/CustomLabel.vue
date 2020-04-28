<template>
    <component 
    	:is="customLabel" 
    	:vkompo="component"
    	:kompoid="kompoid" />
</template>

<script>
import HasVueComponent from '../../element/mixins/HasVueComponent'

export default {
    mixins: [HasVueComponent],
    props: {
        vkompo: {type: String|Object},
    	kompoid: { type: String, required: false }
    },
    computed: {
    	customLabel(){
    		return this.isStringLabel ? 'VlPlainSpan' : this.$_vueTag(this.vkompo)
    	},
    	component(){
    		return this.isStringLabel ? {label: this.vkompo} : this.vkompo
    	},
        isStringLabel(){
            return _.isString(this.vkompo)  || !this.vkompo
        }
    }
}
</script>