<template>
    <div class="cursor-pointer vlFlexCenter">
        <div class="vlFlexCenter flex-col md:flex-row">
            <div class="p-2">
                <div class="vlText text-xl" v-html="$_prop('label')" />
                <div class="text-gray-600 text-xs" v-html="$_prop('comment')" />
            </div>
            <vl-success-icon :success="$_prop('check')" class="vlCheck text-4xl"/>
        </div>
    </div>
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card]
}
</script>

<style lang="scss" scoped>
.vlCheck{
    display: none;
}
.vlSelected{
    .vlText{
        font-weight: bold;
    }
    .vlCheck{
        display: block;
    }
}
</style>