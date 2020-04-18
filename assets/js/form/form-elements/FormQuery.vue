<template>

    <vl-query v-bind="$_attributes" @click.stop="$_clickAction" />

</template>

<script>
import Other from '../mixins/Other'

export default {
    mixins: [Other],
    computed:{
        $_attributes(){
            return {
                ...this.$_defaultOtherAttributes,
                vkompo: this.vkompo,
                kompoid: this.kompoid
            }
        }
    }
}
</script>
