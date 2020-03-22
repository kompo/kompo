<template>
    <div class="row vlImageWithThumbnails" :id="'vlImageWithThumbnails-'+prop('id')">
    	<div class="col-2">&nbsp;</div>
		<div class="col-md-5 d-none d-md-block" style="padding-top:45vh">
			<div class="img-thumbnail d-inline-block" 
				style="width:100px;height:100px;overflow: hidden;margin: 5px 5px 0 0"
				@click="preview(index)"
				v-for="(photo, index) in prop('photos')">
				<img :src="photo" class="img-fluid" style="margin-top:-70px">
			</div>
            <div style="width: 340px">
                <small v-html="prop('previewComment')" />
                <iframe v-if="prop('preview')" frameborder="0" width="340" height="60" :src="prop('preview')"/>
                <small v-html="prop('comment')" />
            </div>
		</div>
		<div class="main-image col-md-5 col-10">
			<i class="fa fa-chevron-left" @click="previousImage()" />
			<img :src="mainPhotoSrc">
			<i class="fa fa-chevron-right" @click="nextImage()" />
            <div class="d-md-none text-center" style="width: 300px; margin: 1rem auto">
                <small v-html="prop('previewComment')" />
                <iframe v-if="prop('preview')" frameborder="0" width="300" height="60" :src="prop('preview')"/>
                <small v-html="prop('comment')" />
            </div>
		</div>
	</div>
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card],
    data(){
    	return {
    		mainPhotoSrc: '',
    		mainPhotoIdx: 0
    	}
    },
    mounted(){
    	this.mainPhotoSrc = this.prop('photos').length ? this.prop('photos')[0] : ''
    },
    methods:{
        preview(index){
            this.mainPhotoIdx = index
            this.mainPhotoSrc = this.prop('photos')[this.mainPhotoIdx]
        },
        previousImage(){
            this.preview(this.mainPhotoIdx == 0 ? this.prop('photos').length - 1 : this.mainPhotoIdx - 1)
        },
        nextImage(){
            this.preview(this.mainPhotoIdx == this.prop('photos').length - 1 ? 0 : this.mainPhotoIdx + 1)
        },
    }
}
</script>
