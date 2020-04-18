<template>
    <component 
    	:is="customLabel" 
    	:vkompo="component"
    	:kompoid="kompoid" />
</template>

<script>
export default {
    props: {
        vkompo: {type: String|Object, default: 'label'},
    	kompoid: { type: String, required: true }
    },
    computed: {
    	customLabel(){
    		return (_.isString(this.vkompo) || !this.vkompo) ? 'VlPlainSpan' : 'Vl'+this.vkompo.component
    	},
    	component(){
    		return (_.isString(this.vkompo)  || !this.vkompo) ? {label: this.vkompo} : this.vkompo
    	}
    }
}
</script>