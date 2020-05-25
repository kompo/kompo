<template>
    <div class="vlDeleteLinkModal">
        <h3>{{ deleteTitle }}</h3>
		<div v-if="!deletedMessage">
		    <vl-button class="vlBtn vlBtnBlock" 
		    	:vkompo="vkompo"
                :kompoid="kompoid"
		    	@deleted="deleted">{{ confirmMessage }}</vl-button>
		    <button 
                type="button" 
                class="vlBtn vlBtnOutlined" 
                @click.stop="closeModal">{{ cancelMessage }}</button>
		</div>
        <div v-else v-html="deletedMessage" />
    </div>
</template>

<script>
export default {
    props: {
        vkompo: {type: Object, required: true},
        kompoid: {type: String, required: true},
        index: {type: Number} //because addlink doesn't have an index
    },
    data(){
        return{
            deletedMessage: ''
        }
    },
    computed:{
        deleteTitle(){
            return this.vkompo.data.deleteTitle
        },
    	confirmMessage(){
    		return this.vkompo.data.confirmMessage
    	},
    	cancelMessage(){
    		return this.vkompo.data.cancelMessage
    	}
    },
    methods: {
    	closeModal(){
    		this.$emit('closeModal')
    	},
        deleted(){
            this.$emit('refresh', this.index)
            this.closeModal()
        }
    }
}
</script>

<style lang="scss" scoped>
.vlDeleteLinkModal{
    padding: 1rem;
    h3{
        margin-bottom: 1rem;
    }
    button{
        width: 10rem;
    }
    >div{
        width: 21rem;
        display: flex;
        justify-content: space-between;
    }
}
</style>
