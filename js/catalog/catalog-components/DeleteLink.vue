<template>
	
	<vl-link 
        v-if="!$_displayNone" v-show="!$_hidden"
        v-bind="$_attributes" 
        @click="confirmDelete">

        <label v-html="$_label" />
     

    </vl-link>

</template>

<script>
import Other from 'vuravel-form/js/mixins/Other'
export default {
    mixins: [Other],
    props: {
        index: {type: Number}, //because addlink doesn't have an index
    },
    data(){
        return {
            confirmComponent: null
        }
    },
    computed:{
        deleteTitle(){ return this.component.deleteTitle },
        $_attributes(){
            return {
                ...this.$_defaultOtherAttributes,
                vcomponent: Object.assign({}, this.confirmComponent), 
                title: this.deleteTitle,
                vuravelid: this.vuravelid
            }
        }
    },
    methods: {
        confirmDelete(){
            this.$modal.insertModal(
                this.vuravelid, {
                    vcomponent: this.vcomponent,
                    is: this.$options.name+ 'ModalContent',
                    index: this.index,
                    vuravelid: this.vuravelid
                },
                {}
            )
        }
    },
    created(){
        var confirmComponent = _.cloneDeep(this.vcomponent)
        confirmComponent.triggers = {}
        this.confirmComponent = confirmComponent
    }
}
</script>
