<template>
    <vl-button
    	type="button" 
    	data-toggle="collapse" 
    	:data-target="target" 
    	aria-expanded="false" 
    	:aria-controls="target"
         :vkompo="{}">

        <slot />

    </vl-button>
</template>


<script>
export default {
	props: ['target'],
    mounted(){

    }
}
</script>
