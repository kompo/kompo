<template>
    <tbody>
        <template v-for="(item, index) in items">
            <component
                v-bind="$_attributes(item, index)"
                @activate="activate"
            />
        </template>
    </tbody>
</template>

<script>
import Layout from '../mixins/Layout'

export default {
    mixins: [Layout]
}
</script>
