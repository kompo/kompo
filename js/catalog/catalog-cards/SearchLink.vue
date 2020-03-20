<template>
	<a :href="$_prop('href')" v-html="$_prop('html')" class="vlInlineBlock vlWFull" />
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card]
}
</script>
