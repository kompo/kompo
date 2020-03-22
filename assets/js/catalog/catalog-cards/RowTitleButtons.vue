<template>
    <div class="vlRowTitleButtons">
        <div class="row">
            <div class="col-9">
                <h4 v-html="$_prop('title')" />
                <p v-html="$_prop('description')" />
            </div>
            <div class="col-3">
                <component 
                    v-if="$_prop('buttons')"
                    v-bind="componentAttributes($_prop('buttons'))"/>
            </div>
        </div>
    </div>
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card]
}
</script>
