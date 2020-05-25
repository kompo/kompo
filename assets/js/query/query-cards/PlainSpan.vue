<template>
    <span v-html="vkompo.label" />
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card]
}
</script>

<style lang="scss" scoped>
.vlSelected{
    font-weight: bold;
}
</style>