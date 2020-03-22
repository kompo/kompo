<template>
    <div class="vlMasonry">
        <div v-masonry transition-duration="0.3s" item-selector=".item">
            <template v-for="(item, index) in items">
                <component
                    v-masonry-tile 
                    class="item"
                    v-bind="$_attributes(item, index)"
                />
            </template>
        </div>
    </div>
</template>

<script>
import Layout from '../mixins/Layout'

export default {
    mixins: [Layout],
    methods:{
        
    }
}
</script>
