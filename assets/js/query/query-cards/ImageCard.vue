<template>
    <a class="vlImageCard"
        :class="$_prop('col')" 
        :href="$_prop('url') || 'javascript:void(0)'" >

        <div :class="$_prop('class')">

            <div v-if="$_prop('image')" 
                :style="{
                    'background-image': 'url('+$_prop('image')+')', 
                    'background-size': 'cover',
                    'background-position': 'center',
                    'height': $_prop('imageHeight') || '10rem'
                }"/>

            <h2 v-if="$_prop('title')" v-html="$_prop('title')" 
                :class="$_prop('titleClass') || 'px-3 my-2 leading-tight' " :style="{
                    '-webkit-line-clamp': $_prop('titleLines') || 2
                }"/>

            <p v-if="$_prop('text')" v-html="$_prop('text')"
               :class="$_prop('textClass') || 'px-3 my-2' " :style="{
                    '-webkit-line-clamp': $_prop('textLines') || 3
                }"/>
            
            <component 
                v-if="$_prop('buttons')"
                v-bind="componentAttributes($_prop('buttons'))"/>

        </div>
        
    </a>
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card]
}
</script>

<style lang="scss" scoped>
.vlImageCard{
    >div{
        >h2, >p{
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            user-select: all;
        }
    }
}
</style>
