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
        vkompo: {type: String|Object, default: 'label'},
    	kompoid: { type: String, required: true }
    },
    computed: {
    	customLabel(){
    		return (_.isString(this.vkompo) || !this.vkompo) ? 'VlPlainSpan' : this.$_vueTag(this.vkompo)
    	},
    	component(){
    		return (_.isString(this.vkompo)  || !this.vkompo) ? {label: this.vkompo} : this.vkompo
    	}
    }
}
</script>