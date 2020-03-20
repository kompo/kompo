<template>
    <div :key="layoutKey">
        <div v-if="!component.orderable">

            <template v-for="(item, index) in items">
                <component v-bind="$_attributes(item, index)" />
            </template>

        </div>

        <draggable v-else v-bind="$_sortingAttributes" v-on="$_sortingEvents">

            <template v-for="(item, index) in items">

                <component v-bind="$_attributes(item, index)" />

            </template>

        </draggable>
    </div>
</template>

<script>
import Layout from '../mixins/Layout'
import draggable from 'vuedraggable'

export default {
    mixins: [Layout],
    components: { draggable }
}
</script>
