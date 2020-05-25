<template>
    <div v-bind="$_layoutWrapperAttributes" v-show="!$_hidden">
        <component v-for="(col,index) in komponents"
            :key="index"
            v-bind="$_attributes(col)" />
    </div>
</template>

<script>
import Layout from '../mixins/Layout'

export default {
    mixins: [Layout],
    computed:{
        $_customClassArray(){
            return [
                this.$_data('justifyClass'),
                this.$_data('alignClass') 
            ]
        },
    }
}
</script>
