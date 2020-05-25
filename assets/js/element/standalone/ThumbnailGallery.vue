<template>
    <div>
        <draggable 
        	v-if="images.length > 0" 
        	:list="images" 
            class="gallery-list clearfix"
            :class="images.length > 4 ? 'large-gallery' : '' " >
            <transition-group name="gallery-transition">
              	<vl-thumbnail 
              		v-for="(image, index) in images"
                    :key="index + 0" 
                    :image="image" 
                    :height="calculatedHeight"
                    :index="index"
                    @remove="remove"
                    @preview="previewAndOpen" />
            </transition-group>
        </draggable>

        <vl-thumbnail-modal 
            :name="modalname"
            :image="previewImage" 
            :length="images.length"
            @next="nextImage"
            @previous="previousImage" />

    </div>
</template>

<script>

import draggable from 'vuedraggable'

export default {
    props: ['images', 'height'],

    components: { draggable },

    data: () => ({
        previewIndex: null,
        previewImage: null,
        modalname:  null
    }),

    computed: {
        calculatedHeight(){
            return this.height ? this.height : (this.images.length > 4 ? '2.9rem' : '6.3rem')
        }
    },

    methods: {
        previewAndOpen(index){
            this.preview(index)
            this.$modal.show(this.modalname)
        },
        preview(index){
            this.previewIndex = index
            this.previewImage = this.images[this.previewIndex]
        },
        previousImage(){
            this.preview(this.previewIndex == 0 ? this.images.length - 1 : this.previewIndex - 1)
        },
        nextImage(){
            this.preview(this.previewIndex == this.images.length - 1 ? 0 : this.previewIndex + 1)
        },
        remove(index){
            this.$emit('remove',index)
        }
    },
    mounted(){
        this.modalname = 'image-preview'+this._uid
    }
}
</script>