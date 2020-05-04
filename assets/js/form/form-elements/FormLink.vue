<template>
    <a
        v-if="!$_displayNone" v-show="!$_hidden"
        v-bind="$_attributes" 
        v-turbo-click="component.turbo"
        @click="$_clickAction">

        <label v-html="$_label" />

        <span v-if="!$_hideIndicators">
            <vl-spinner-icon :loading="showSpinner" />
            <vl-success-icon :success="showCheckmark" />
            <vl-error-icon :error="showError" />
        </span>

        <vl-form-info :component="component" />
    
    </a>
</template>

<script>
import Trigger from '../mixins/Trigger'
export default {
    mixins: [Trigger],
    computed:{
        $_attributes(){
            return {
                ...this.$_defaultTriggerAttributes,
                href: this.component.href || 'javascript:void(0)',
                target: this.component.target
            }
        },
        $_customClassArray(){
            return [
                this.linkClass
            ]
        },
        linkClass(){
            return (this.$_data('btnStyle') ? 'vlBtn' : 'vlLink') + 
                (this.$_data('btnOutlined') ? ' vlBtnOutlined' : '') +
                (this.$_data('btnPlain') ? ' vlBtnPlain' : '') +
                (this.$_data('secondary') ? ' vlSecondary' : '')+
                (this.$_data('btnInline') ? ' vlBtnInline' : '') +
                (this.$_data('btnBlock') ? ' vlBtnBlock' : '') //inline by default
        }
    }
}
</script>
