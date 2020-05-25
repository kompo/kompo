<template>
	
	<img v-if="image"
        :src="image" 
        :alt="title" 
        class="image-preview">

</template>

<script>

export default {
    props: {
        image: {type: String, required: true},
        title: {type: String}
    }
}
</script>
