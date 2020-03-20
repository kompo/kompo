<template>
    <vl-form-field v-bind="$_wrapperAttributes">
        <textarea
            v-model="component.value"
            class="vlFormControl"

            v-bind="$_attributes"
            v-on="$_events"
        ></textarea>
    </vl-form-field>
</template>

<script>
import Field from '../mixins/Field'
export default {
    mixins: [Field],
    computed: {
        $_attributes(){
            return {
                ...this.$_defaultFieldAttributes,
                rows: this.$_data('rows') || 3
            }
        }
    }
}
</script>

