<template>

	<component 
        v-if="!$_displayNone" v-show="!$_hidden"
        v-bind="$_attributes" 
        @insertForm="insertForm">

        <label v-html="$_label" />
     
    </component>

</template>

<script>
import Other from 'vuravel-form/js/mixins/Other'

export default {
    mixins: [ Other ],
    props: {
        index: {type: Number}, //because addlink doesn't have an index
    },
    computed:{
       	linkTag(){ return this.component.linkTag },
        keepModalOpen(){ return this.$_data('keepModalOpen') },
        $_attributes(){
            return {
                ...this.$_defaultOtherAttributes,
                vcomponent: Object.assign({}, this.vcomponent), 
                is: this.linkTag,
                vuravelid: this.vuravelid
            }
        }
    },
    methods:{
        insertForm(form){
            this.$modal.events.$emit(
                'insertModal' + this.vuravelid, 
                this.componentProps(form), 
                this.modalProps()
            )
        },

        componentProps(form){
            return {
                vcomponent: form,
                is: 'VlEditLinkModalContent',
                index: this.index,
                vuravelid: this.vuravelid,
                keepModalOpen: this.keepModalOpen
            }
        },
        modalProps(){
            return {
                warn: this.$_warnBeforeClose
            }
        }
    }
}
</script>
