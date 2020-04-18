<template>
    <div class="vlNoItems" v-html="noItemsFound" />
</template>

<script>
export default {
    props: {
        noItemsFound: {type: String, required: true}
    }
}
</script>
