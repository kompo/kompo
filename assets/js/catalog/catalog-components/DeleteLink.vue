<template>
	
	<vl-link 
        v-if="!$_displayNone" v-show="!$_hidden"
        v-bind="$_attributes" 
        @click="confirmDelete">

        <label v-html="$_label" />
     

    </vl-link>

</template>

<script>
import Other from '../../form/mixins/Other'
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
                kompoid: this.kompoid
            }
        }
    },
    methods: {
        confirmDelete(){
            this.$modal.insertModal(
                this.kompoid, {
                    vcomponent: this.vcomponent,
                    is: this.$options.name+ 'ModalContent',
                    index: this.index,
                    kompoid: this.kompoid
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
