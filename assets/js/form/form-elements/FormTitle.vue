<template>

    <h1 v-bind="$_attributes" v-show="!$_hidden">

    	<span v-html="$_label" />

    	<vl-form-info :component="component" />

    </h1>

</template>

<script>
import Other from '../mixins/Other'

export default {
    mixins: [Other]
}
</script>
