<template>
    <div class="vlRowTitle flex justify-between" :class="prop('class')">

        <i v-if="prop('icon')" :class="prop('icon')" />

        <vlEditLink 
            v-if="prop('edit')"
            :vuravelid="vuravelid"
            :vcomponent="prop('edit')" />

        <span v-else v-html="prop('title')" />

        <vlDeleteLink 
            v-if="prop('delete')"
            :vuravelid="vuravelid"
            :vcomponent="prop('delete')" />

    </div>
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card]
}
</script>
