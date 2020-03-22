<template>

    <th v-bind="$_attributes" @click.stop="$_clickAction">
    	<span v-html="$_label" />
    	<i :class="iconClass" />
    </th>

</template>

<script>
import Other from '../../form/mixins/Other'

export default {
    mixins: [Other],
    data(){
    	return {
    		sortColumn: '',
    		sortDirection: '',
    		initialDirection: ''
    	}
    },
    created(){
    	if(this.$_sortsCatalog){
	    	var sortSpecs = this.$_sortsCatalog.split(':')
	    	this.sortColumn = sortSpecs[0]
	    	this.initialDirection = sortSpecs.length == 2 ? sortSpecs[1] : ''
	    }
    },
    computed: {
        $_customClassArray() { 
            return [
                this.$_sortsCatalog ? 'cursor-pointer' : null
            ] 
        },
        activeSort(){
        	return this.$_sortsCatalog && this.$_state('activeSort')
        },
        sortingDesc(){ return this.activeSort && this.sortDirection == 'DESC' },
        sortingAsc(){ return this.activeSort && this.sortDirection == 'ASC' },
        iconClass(){
        	return this.sortingDesc ? 'icon-down' : (this.sortingAsc ? 'icon-up' : '')
        },
        $_sortValue(){
        	return this.sortDirection ? (this.sortColumn+':'+this.sortDirection) : ''
        },	
    },
    methods: {
    	customBeforeSort(){
    		if(this.initialDirection == 'DESC'){
    			this.sortDirection = this.sortDirection == '' ? 'DESC' : 
    				(this.sortDirection == 'DESC'? 'ASC' : '')
	    	}else{
	    		this.sortDirection = this.sortDirection == '' ? 'ASC' : 
	    			(this.sortDirection == 'DESC' ? '' : 'DESC')
	    	}
    	}
    }
}
</script>
