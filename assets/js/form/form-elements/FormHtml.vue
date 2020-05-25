<template>

    <div 
        v-if="!$_displayNone" 
        v-show="!$_hidden" 
        v-bind="$_attributes" 
        v-html="$_label"  />

</template>

<script>
import Other from '../mixins/Other'

export default {
    mixins: [Other]
}
</script>
