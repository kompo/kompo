<template>
    <component v-bind="componentAttributes(component)" />
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card]
}
</script>
