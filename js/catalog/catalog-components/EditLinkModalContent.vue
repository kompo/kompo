<template>

	<vl-form :vcomponent="editForm"  @success="success" />

</template>

<script>

export default {
    props: {
        vcomponent: {type: Object, required: true},
        vuravelid: {type: String, required: true},
        index: {type: Number}, //because addlink doesn't have an index
        keepModalOpen: {type: Boolean, default: false}
    },
    data(){
        return {
            editForm: {}
        }
    },
    created(){
        this.editForm = this.vcomponent
    },
    methods:{
        reloadFreshForm(){
            this.$emit('openModal')
        },
        success(response){
            this.$emit('refresh', this.index)
            if(!this.keepModalOpen){
                this.$emit('closeModal')
            }
        }
    }
}
</script>
