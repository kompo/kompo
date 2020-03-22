<template>
    <vl-modal 
    	:name="name" 
        :arrows="length > 1"
    	class="vlGalleryModal"
        @previous="$emit('previous')" 
        @next="$emit('next')">

        <vl-spinner-icon :loading="!image || !image.src" />

        <img v-if="image && image.src"
            :src="image.src" 
            :alt="image.name" 
            class="image-preview">

    </vl-modal>
</template>

<script>

export default {
    props: ['name','image','length']
}
</script>
