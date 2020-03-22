<template>

    <i 
    	v-if="info" 
    	:title="info"
        data-toggle="tooltip" 
        data-html="true"
        :data-placement="placement" 
        :class="infoIcon" />

</template>

<script>

export default {
    props: ['component'],
    computed: {
        info(){
            return this.component.$_data('info')
        },
        placement(){
            return this.component.$_data('infoPlacement') || 'top'
        },
        infoIcon(){
            return this.component.$_data('infoIcon') || 'icon-question-circle'
        }
    }
}
</script>