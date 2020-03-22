<template>
    <div>
        <form v-bind="formAttributes" class="vlForm">
            <template v-for="component in components">
                <component 
                    v-bind="$_attributes(component)"
                    :class="$_vlMargins(component)" />
            </template>
        </form>
        <vl-support-modal :kompoid="$_kompoId" />
    </div>
</template>

<script>
import Layout from './mixins/Layout'
export default {
    mixins: [Layout],

    data(){
        return {
            canSubmit: true
        }
    },

    computed: {
        formAttributes(){
            return {
                ...this.$_defaultElementAttributes,
                class: this.$_phpClasses,
                style: this.$_elementStyles
            }
        },

        emitFormData(){ return this.$_data('emitFormData') },

        formUrl(){ return this.$_data('submitUrl') },
        formMethod(){ return this.$_data('submitMethod') },

        redirectUrl(){ return this.$_data('redirectUrl') },
        redirectMessage(){ return this.$_data('redirectMessage') }
    },

    methods: {
        preSubmit(){
            if(this.emitFormData)
                this.$emit('submit', this.getJsonFormData())
        },
        submitSuccess(r){

            this.$emit('success', r)
            
            //redirect route predefined in form
            if(this.redirectUrl){
                this.setRedirecting()
                setTimeout( () => {this.redirect(this.redirectUrl)}, 300)
            }
            //redirect route coming from controller
            if(this.formUrl != r.request.responseURL){
                this.setRedirecting()
                setTimeout( () => {this.redirect(r.request.responseURL)}, 300)
            }
            //check responseInModal() helper
            if(r.data.inModal)
                this.$modal.showFill('modal'+this.$_elementId(), r.data.message || r.data)

            if(r.status === 202){
                this.destroyEvents()
                this.$emit('refreshForm', r.data.form)
            }
        },
        submitError(e){

            this.$emit('error', e)

            if (e.response.status == 422){
                this.$_validate(e.response.data.errors)
                this.$modal.events.$emit('showAlert', 'Please correct the errors', 'vlAlertError')
            }else{
                this.$modal.events.$emit('showAlert', 'Error '+e.response.status+' | '+e.response.data.message, 'vlAlertError')
            }
        },
        getJsonFormData(){
            var jsonFormData = {}
            this.$_fillRecursive(jsonFormData)
            return jsonFormData
        },
        setRedirecting(){
            this.$_state({ redirecting: this.redirectMessage })
        },
        redirect(url) {
            window.location.href = url
        },

        attachEvents(){
            this.$_vlOn('vlPreSubmit'+this.$_elementId(), () => {
                this.preSubmit()
            })
            this.$_vlOn('vlSubmitSuccess'+this.$_elementId(), (response) => {
                this.submitSuccess(response)
            })
            this.$_vlOn('vlSubmitError'+this.$_elementId(), (error) => {
                this.submitError(error)
            })
            this.$_vlOn('vlToggle'+this.$_elementId(), (toggleId) => {
                this.$_toggle(toggleId)
            })
            this.$_vlOn('vlUpdateErrorState'+this.$_elementId(), () => {
                var errors = []
                this.$_getErrors(errors)
            })
            this.$_vlOn('vlDeliverJsonFormData'+this.$_elementId(), (toComponentId) => {
                this.$_deliverJsonTo(toComponentId, this.getJsonFormData())
            })
            this.$_vlOn('vlToggleSubmit'+this.$_elementId(), (canSubmit) => {
                this.canSubmit = canSubmit
            })
            this.$_vlOn('vlRequestFormInfo'+this.$_elementId(), (askerId) => {
                this.$kompo.vlDeliverFormInfo(askerId, {
                    canSubmit: this.canSubmit,
                    jsonFormData: this.getJsonFormData(),
                    url: this.formUrl, 
                    method: this.formMethod
                })
            })
        },
        destroyEvents(){
            this.$_vlOff([
                'vlPreSubmit'+this.$_elementId(),
                'vlSubmitSuccess'+this.$_elementId(),
                'vlSubmitError'+this.$_elementId(),
                'vlToggle'+this.$_elementId(),
                'vlUpdateErrorState'+this.$_elementId(),
                'vlDeliverJsonFormData'+this.$_elementId(),
                'vlToggleSubmit'+this.$_elementId(),
                'vlRequestFormInfo'+this.$_elementId()
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
