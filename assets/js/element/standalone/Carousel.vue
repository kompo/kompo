<template>
    <div class="vlCarousel">
    	<div class="vlCarouselInner">
    		<transition-group name="slideleft">
	    		<img class="vlCarouselImage vlCarouselImageLeft" 
	    			:src="visibleSlides[0].image" 
	    			:key="visibleSlides[0].id">
		    	<img class="vlCarouselImage" 
		    		:src="visibleSlides[1].image"
		    		:key="visibleSlides[1].id">
		    	<img class="vlCarouselImage vlCarouselImageRight" 
		    		:src="visibleSlides[2].image"
		    		:key="visibleSlides[2].id">
		    </transition-group>
	    </div>
    	<div class="arrow arrow-left" @click.stop="previous()"><i class="fa fa-chevron-left fa-2x" /></div>
    	<div class="arrow arrow-right" @click.stop="next()"><i class="fa fa-chevron-right fa-2x" /></div>
    </div>

</template>

<script>
export default {
    props: {
    	slides : {
    		type: Array,
    		required: true
    	}
    },
    created(){
    	this.visibleSlides = this.slides.slice(0,3)
    },
    data(){
    	return {
    		visibleSlides: [],
    		previewIndex: 0
    	}
    },
    methods: {    	
        preview(index){
            this.previewIndex = index
            if(this.previewIndex <= this.slides.length - 3){
            	this.visibleSlides = this.slides.slice(this.previewIndex, this.previewIndex+3)
            }else{
            	this.visibleSlides = this.slides.slice(this.previewIndex, this.previewIndex+3)
            		.concat(this.slides.slice(0, this.previewIndex + 3 - this.slides.length ))
            }
        },
        previous(){
            this.preview(this.previewIndex == 0 ? this.slides.length - 1 : this.previewIndex - 1)
        },
        next(){
            this.preview(this.previewIndex == this.slides.length - 1 ? 0 : this.previewIndex + 1)
        },
    }
}
</script>

<style lang="scss">
.slideleft-enter-active {
  transition: all .3s ease;
}
.slideleft-leave-active {
  transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slideleft-enter, .slideleft-leave-to{
  	transform: translateX(10px);
  	opacity: 0;
}

.vlCarousel{
	.arrow{
		position: absolute;
		top: 38vh;
		padding: 0.6rem 0.9rem 0.4rem;
		background-color: rgba(0,0,0,0.5);
		color: white;
		&.arrow-right{
			right: 1rem;
		}
		&.arrow-left{
			left: 1rem;
		}
	}
	overflow: hidden;
	.vlCarouselInner{
		width: 100vw;
		position: relative;
		.vlCarouselImage{
			max-height: 80vh;
			max-width: 80%;
			margin: 0 auto;
			display: block;
		}
		.vlCarouselImageLeft{
			position: absolute;
			top: 0;
			right: 90vw;
		}
		.vlCarouselImageRight{
			position: absolute;
			top: 0;
			left: 90vw;
		}
	}
}
</style>