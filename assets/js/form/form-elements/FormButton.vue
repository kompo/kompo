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
import Trigger from '../mixins/Trigger'
export default {
    mixins: [Trigger],
    computed:{
        $_attributes(){
            return {
                ...this.$_defaultTriggerAttributes,
                vkompo: Object.assign({}, this.vkompo), //otherwise $_state wasn't rendering...
                disabled: this.showSpinner,
                kompoid: this.kompoid
            }
        }
    }
}
</script>
