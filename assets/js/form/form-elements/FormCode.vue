<template>

    <pre v-if="!$_displayNone" v-show="!$_hidden" v-bind="$_attributes"
    ><code class="language-php" v-html="$_label" /></pre>

</template>

<script>
import Other from '../mixins/Other'

export default {
    mixins: [Other]
}
</script>
