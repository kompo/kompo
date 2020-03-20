<template>
	<input :type="inputType" v-model="component.value" :id="$_elementId()" />
</template>

<script>
import Field from '../mixins/Field'
export default {
    mixins: [Field],
    computed: {
    	inputType(){
    		return this.component.showHidden ? 'text' : 'hidden'
    	}
    }
}
</script>

