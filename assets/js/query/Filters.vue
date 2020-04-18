<template>
    <div v-if="filters.length" :class="'vlFilters'+placement">
        <template v-for="filter in filters">
            <component
                :is="'Vl' + filter.component"
                :vkompo="filter"
                :kompoid="kompoid"
                class="vlMargins"
            />
        </template>
    </div>
</template>

<script>
export default {

    props: {
        filters: { type: Array, required: true },
        kompoid: { type: String, required: true },
        placement: {type: String, required: true }
    },

    methods: {
        filterPlacement(){
            return 'vlFilters'+this.placement
        }
    }
}

</script>
