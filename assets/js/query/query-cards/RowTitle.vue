<template>
    <div class="vlRowTitle flex justify-between" :class="$_prop('class')">

        <i v-if="$_prop('icon')" :class="$_prop('icon')" />

        <vlEditLink 
            v-if="$_prop('edit')"
            :kompoid="kompoid"
            :vkompo="$_prop('edit')" />

        <span v-else v-html="$_prop('title')" />

        <vlDeleteLink 
            v-if="$_prop('delete')"
            :kompoid="kompoid"
            :vkompo="$_prop('delete')" />

    </div>
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card]
}
</script>
