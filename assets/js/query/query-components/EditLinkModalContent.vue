<template>

	<vl-form :vkompo="editForm"  @success="success" />

</template>

<script>

export default {
    props: {
        vkompo: {type: Object, required: true},
        kompoid: {type: String, required: true},
        index: {type: Number}, //because addlink doesn't have an index
        keepModalOpen: {type: Boolean, default: false}
    },
    data(){
        return {
            editForm: {}
        }
    },
    created(){
        this.editForm = this.vkompo
    },
    methods:{
        success(response, submitKomponent){
            this.$emit('refresh', this.index)

            if(!this.keepModalOpen && !submitKomponent.$_keepModalOpen)
                this.$emit('closeModal')
        }
    }
}
</script>
