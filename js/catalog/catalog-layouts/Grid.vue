<template>
    <div class="row">
        <template v-for="(item, index) in items">
            <component
                v-bind="$_attributes(item, index)"
            />
        </template>
    </div>
</template>

<script>
import Layout from '../mixins/Layout'

export default {
    mixins: [Layout]
}
</script>
