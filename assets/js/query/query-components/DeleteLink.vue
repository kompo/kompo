<template>
	
	<vl-link 
        v-if="!$_displayNone" v-show="!$_hidden"
        v-bind="$_attributes" 
        @click="confirmDelete">

        <label v-if="$_label" v-html="$_label" />
        <i v-else class="icon-trash" :title="deleteTitle" />
     

    </vl-link>

</template>

<script>
import Trigger from '../../form/mixins/Trigger'
export default {
    mixins: [Trigger],
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
                ...this.$_defaultTriggerAttributes,
                vkompo: Object.assign({}, this.confirmComponent), 
                title: this.deleteTitle,
                kompoid: this.kompoid
            }
        }
    },
    methods: {
        confirmDelete(){
            this.$modal.insertModal(
                this.kompoid, {
                    vkompo: this.vkompo,
                    is: this.$options.name+ 'ModalContent',
                    index: this.index,
                    kompoid: this.kompoid
                },
                {}
            )
        }
    },
    created(){
        var confirmComponent = _.cloneDeep(this.vkompo)
        confirmComponent.interactions = {}
        this.confirmComponent = confirmComponent
    }
}
</script>
