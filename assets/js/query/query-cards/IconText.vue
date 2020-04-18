<template>
    <a :href="$_prop('url') || 'javascript:void(0)'"
    	class="vlIconText" 
    	:class="$_prop('col')">
    	<div :class="$_prop('class')" :style="divStyle">
	        <i :class="$_prop('icon')" />
	        <h3 v-html="$_prop('text')" />
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
.vlSelected>div>h3{
    font-weight: bold;
}
.vlIconText>div{
	display: flex;
	width: 100%;
	align-items: center;
	padding: 0.5rem;
	>i{
		width: 3rem;
		text-align: center;
	}
	>h3{
		flex: 1;
	}
}
</style>
