<template>
    <div class="row">
        <div class="col-4" style="overflow: hidden">
            <img 
                v-if="prop('image')" 
                :src="prop('image')"
                height="60">
        </div>
        <h3 class="col-8">
        	{{ prop('title') }}
	    </h3>
    </div>
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card]
}
</script>
