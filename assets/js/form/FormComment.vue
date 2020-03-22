<template>
    
    <small v-if="comment" v-html="comment" class="vlFormComment"/>

</template>

<script>

export default {
    props: ['component'],
    computed: {
        comment(){
            return this.component.$_data('comment')
        }
    }
}
</script>