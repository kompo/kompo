<template>
    <vl-button 
        v-if="!$_displayNone" v-show="!$_hidden"
        v-bind="$_attributes"
        @click="$_clickAction">

        <label v-html="$_label" />

        <span v-if="!$_hideIndicators">
            <vl-spinner-icon :loading="showSpinner" />
            <vl-success-icon :success="showCheckmark" />
            <vl-error-icon :error="showError" />
        </span>

        <vl-form-info :component="component" />

    </vl-button>
</template>

<script>
import Other from '../mixins/Other'
export default {
    mixins: [Other],
    computed:{
        $_attributes(){
            return {
                ...this.$_defaultOtherAttributes,
                vcomponent: Object.assign({}, this.vcomponent), //otherwise $_state wasn't rendering...
                disabled: this.showSpinner,
                vuravelid: this.vuravelid
            }
        }
    }
}
</script>
