<template>

	<component 
        v-if="!$_displayNone" v-show="!$_hidden"
        v-bind="$_attributes" 
        @insertForm="insertForm">

        <label v-html="$_label" />
     
    </component>

</template>

<script>
import Trigger from '../../form/mixins/Trigger'

export default {
    mixins: [ Trigger ],
    props: {
        index: {type: Number}, //because addlink doesn't have an index
    },
    created(){
        this.component.interactions[0].action.interactions.push(this.component.defaultSuccessInteraction)
    },
    computed:{
       	linkTag(){ return this.component.linkTag },
        keepModalOpen(){ return this.$_data('keepModalOpen') },
        warnBeforeClose(){ return this.$_data('warnBeforeClose') },
        $_attributes(){
            return {
                ...this.$_defaultTriggerAttributes,
                vkompo: Object.assign({}, this.vkompo), 
                is: this.linkTag,
                kompoid: this.kompoid
            }
        }
    },
    methods:{
        insertForm(form){
            this.$modal.events.$emit(
                'insertModal' + this.kompoid, 
                this.componentProps(form), 
                this.modalProps()
            )
        },

        componentProps(form){
            return {
                vkompo: form,
                is: 'VlEditLinkModalContent',
                index: this.index,
                kompoid: this.kompoid,
                keepModalOpen: this.keepModalOpen
            }
        },
        modalProps(){
            return {
                warn: this.warnBeforeClose
            }
        }
    }
}
</script>
