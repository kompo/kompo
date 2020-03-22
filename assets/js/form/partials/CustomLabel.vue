<template>
    <component 
    	:is="customLabel" 
    	:vcomponent="component"
    	:kompoid="kompoid" />
</template>

<script>
export default {
    props: {
        vcomponent: {type: String|Object, default: 'label'},
    	kompoid: { type: String, required: true }
    },
    computed: {
    	customLabel(){
    		return _.isString(this.vcomponent) ? 'VlPlainSpan' : 'Vl'+this.vcomponent.component
    	},
    	component(){
    		return _.isString(this.vcomponent) ? {
    			label: this.vcomponent
    		} : this.vcomponent
    	}
    }
}
</script>