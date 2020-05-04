<template>
    <VlFormInner 
        :vkompo="component"
        :key="formKey"
        @refreshForm="refreshForm"
        @submit="submit"
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
            this.component = Object.assign({}, this.vkompo)
            this.formKey += 1
        },
        successEvent(response, submitKomponent){
            this.$emit('success',response, submitKomponent)

            if(submitKomponent.$_getFreshForm)
                this.reloadFreshForm()

            if(this.success) //Injected javascript function to be executed on success
                this.success(response)
        },
        errorEvent(response, submitKomponent){
            this.$emit('error',response, submitKomponent)
            if(this.error) //Injected javascript function to be executed on error
                this.error(response)
        },

        $_attachEvents(){
            this.$_vlOn('vlEmit'+this.$_elKompoId, (eventName, eventPayload) => {
                this.$emit(eventName, eventPayload)
                if(this.kompoid)
                    this.$_vlEmitFrom(eventName, eventPayload)
            })
        },
        $_destroyEvents(){
            this.$_vlOff([
                'vlEmit'+this.$_elKompoId,
            ])
        }
    }
}

</script>
