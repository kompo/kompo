<template>
    <VlFormInner 
        :vcomponent="component"
        :key="formKey"
        @refreshForm="refreshForm"
        @submit="submit"
        @addanother="reloadFreshForm"
        @success="successEvent"
        @error="errorEvent"
        />
</template>

<script>
import Element from '../element/mixins/Element'

export default {
    mixins: [Element],

    data(){
        return {
            formKey: 0
        }
    },
    
    props: {
        success: {}, //injected javascript function to be executed on success
        error: {} //injected javascript function to be executed on error
    },

    methods: {
        refreshForm(form){
            this.component = form
            this.formKey += 1
        },
        submit(jsonFormData){
            this.$emit('submit', jsonFormData)
        },
        reloadFreshForm(){
            this.component = Object.assign({}, this.vcomponent)
            this.formKey += 1
        },
        successEvent(response, submitComponent){
            this.$emit('success',response, submitComponent)
            if(this.success) //Injected javascript function to be executed on success
                this.success(response)
        },
        errorEvent(response, submitComponent){
            this.$emit('error',response, submitComponent)
            if(this.error) //Injected javascript function to be executed on error
                this.error(response)
        },

        attachEvents(){
            this.$_vlOn('vlEmit'+this.$_elKompoId, (eventName, eventPayload) => {
                this.$emit(eventName, eventPayload)
                if(this.kompoid)
                    this.$_vlEmitFrom(eventName, eventPayload)
            })
        },
        destroyEvents(){
            this.$_vlOff([
                'vlEmit'+this.$_elKompoId
            ])
        }
    },

    created() {
        this.destroyEvents()
        this.attachEvents()
    },
    updated() {
        this.destroyEvents()
        this.attachEvents()
    }
}

</script>
