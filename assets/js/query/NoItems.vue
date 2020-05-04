<template>
    <div class="vlNoItems" v-html="noItemsFound" />
</template>

<script>
export default {
    props: {
        vkompo: {type: Object, required: true}
    },
    computed: {
    	noItemsFound(){ return this.vkompo.noItemsFound }
    }
}
</script>
