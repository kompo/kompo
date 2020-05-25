<template>
    <vl-tabs 
        :activeTab="activeTab"
        v-bind="$_layoutWrapperAttributes" 
        v-show="!$_hidden">

        <component v-bind="$_attributes(tab)"
            v-for="(tab,index) in komponents"
            :key="index" />
            
    </vl-tabs>
</template>

<script>
import Layout from '../mixins/Layout'

export default {
    mixins: [Layout],
    data(){
    	return {
    		activeTab: 0
    	}
    },
    methods:{
        $_validate(errors) {
            Layout.methods.$_validate.call(this, errors)
            this.komponents.forEach( (tab, index) => {
	            var errors = []
	            tab.$_getErrors(errors)
	            if(errors.length)
                    this.activeTab = index
            })
        }
    }

}
</script>
