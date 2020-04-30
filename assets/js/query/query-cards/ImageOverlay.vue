<template>
    <a class="vlImageOverlay"
    	:href="$_prop('url') || 'javascript:void(0)'" 
    	:class="$_prop('col')" 
    	@click="$_preview">

        <div :class="$_prop('class')"
        	:style="backgroundImage">

            <img v-if="fullImage" class="vlWFull"
            	:src="$_prop('image')" :alt="$_prop('title')">

	        <div :class="overlayClass" class="vlAbsoluteInset vlFlexCenter">
		        <h2 v-if="$_prop('title')" v-html="$_prop('title')" />
		    </div>

	        <component 
	        	v-if="$_prop('buttons')" 
	        	class="vlToolbar vlAbsoluteInset"
	        	v-bind="componentAttributes($_prop('buttons'))"/>
	        
	    </div>

    </a>
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card],
    computed:{
    	fullImage(){
    		return this.layout == 'Masonry' && !this.$_prop('imageHeight')
    	},
    	backgroundImage(){
    		return Object.assign({'position': 'relative'}, this.fullImage ? {} : {
                'background-image': 'url('+this.$_prop('image')+')', 
                'background-size': 'cover',
                'background-position': 'center',
                'height': this.$_prop('imageHeight') || '16rem'
            })
    	},
    	overlayClass(){
    		return this.$_prop('noOverlay') ? '' : 
    			('vlOverlay' + (this.$_prop('title') ? '' : ' vlOverlaySoft'))
    	},
    }
}
</script>

<style lang="scss">
.vlImageOverlay .vlToolbar .vlLink{
	color: white;
}
</style>

<style lang="scss" scoped>
.vlImageOverlay{
	.vlOverlay{
		background-color: rgba(0,0,0,0.6);
		>h2{
			font-size: 1.7rem;
			padding: 0 1rem;
			text-align: center;
			color: white;
			transition: all 0.4s ease-in-out;
		}
	}
	.vlOverlaySoft{
		background-color: rgba(0,0,0,0.2);		
	}
	.vlToolbar{
		position: absolute;
		.vlLink{
			color: white;
		}
	}
	&:hover{
		.vlOverlay,.vlOverlaySoft{
			background-color: rgba(0,0,0,0);
			transition: all 0.2s ease-in-out;
			>h2{
				color: transparent;			
			}
		}
	}
}
</style>