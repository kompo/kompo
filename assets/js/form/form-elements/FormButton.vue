<template>
    <button 
        type="button"
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

    </button>
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
        },
        $_customClassArray(){
            return [
                this.btnClass
            ]
        },
        btnClass(){
            return 'vlBtn' + 
                (this.$_data('btnOutlined') ? ' vlBtnOutlined' : '') +
                (this.$_data('btnPlain') ? ' vlBtnPlain' : '') +
                (this.$_data('btnInline') ? ' vlBtnInline' : '') +
                (this.$_data('btnBlock') ? ' vlBtnBlock' : '') +
                (this.$_data('secondary') ? ' vlSecondary' : '')
        }
    }
}
</script>
