<template>
    <a v-bind="attributes"
        class="vlImageTitleRow" 
        @click.stop="scrollTo('#'+$_prop('slug'))">

        <div :class="$_prop('class')">

            <div :class="$_prop('imageClass')" :style="{
                'background-image': 'url('+$_prop('image')+')',
                height: $_prop('imageHeight') || '8rem',
                width: $_prop('imageWidth') || $_prop('imageHeight') || '8rem'
            }"/>
            
            <div>
                <h2 :class="$_prop('titleClass')" v-html="$_prop('title')"/>
                <p  :class="$_prop('textClass')" v-html="$_prop('text')"/>

                <component 
                    v-if="$_prop('buttons')"
                    v-bind="componentAttributes($_prop('buttons'))"/>
                    
    	    </div>

        </div>

    </a>
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card],
    computed:{
        attributes(){
            return {
                href: this.$_prop('url') ? 
                    (this.$_prop('url') + (this.$_prop('slug') ? ('#'+this.$_prop('slug')) : '')) :
                    (this.$_prop('slug') ? '#' + this.$_prop('slug') : 'javascript:void(0)'),
                id: this.$_prop('slug') || null,
                class: this.$_prop('col') || null
            }
        }
    },
    methods:{
        scrollTo(id){
            $('html, body').animate({scrollTop: $(id).offset().top}, 1000)
        }
    },
    mounted(){
        if(window.location.hash == '#'+this.$_prop('slug'))
            this.scrollTo(window.location.hash)
    }
}
</script>

<style lang="scss" scoped>
.vlSelected>div>h3{
    font-weight: bold;
}
.vlImageTitleRow>div{
    display: flex;
    width: 100%;
    align-items: center;
    >div:first-child{
        background-size: cover;
        background-position: center;
        flex-shrink: 0;
    }
    >div:last-child{
        flex: 1;
    }
}
</style>