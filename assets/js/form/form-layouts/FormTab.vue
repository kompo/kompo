<template>
    <vl-tab 
        :name="$_label" 
        :active="isActive"
        :style="$_elementStyles">
        <component v-bind="$_attributes(tab)"
            v-for="(tab,index) in komponents"
            :key="index" />
    </vl-tab>
</template>

<script>
import Layout from '../mixins/Layout'

export default {
    mixins: [Layout],
    data: () => ({
        isActive: false
    }),
    computed: {
        header() {
            return this.$_label
        }
    }
}
</script>
