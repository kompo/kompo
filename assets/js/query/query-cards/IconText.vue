<template>
    <a :href="$_prop('url') || 'javascript:void(0)'"
    	class="vlIconText" 
    	:class="$_prop('col')">
    	<div :class="$_prop('class')" :style="divStyle">
	        <i :class="$_prop('icon')" />
	        <div :class="$_prop('textClass')" v-html="$_prop('text')" />
	    </div>
    </a>
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card],
    computed:{
    	divStyle(){
    		return this.$_prop('height') ? {height: this.$_prop('height')} : {}
    	}
    }
}
</script>

<style lang="scss" scoped>
.vlSelected>div>div{
    font-weight: bold;
}
.vlIconText>div{
	display: flex;
	width: 100%;
	align-items: center;
	justify-content: space-around;
	padding: 0.5rem;
	>i{
		text-align: center;
	}
	>div{
		flex: 1;
	}
}
</style>
