<template>

    <vl-form v-bind="$_attributes" @click.stop="$_clickAction" />

</template>

<script>
import Other from '../mixins/Other'

export default {
    mixins: [Other],
    computed:{
        $_attributes(){
            return {
                //...this.$_defaultOtherAttributes, //vl-form already does that
                vkompo: this.component,
                kompoid: this.$_elKompoId || this.kompoid
            }
        }
    }
}
</script>
