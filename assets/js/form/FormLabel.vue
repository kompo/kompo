<template>
    
    <label 
        v-if="!noLabel" 
        :for="component.id" 
        :class="labelFullClass" 
        @click="$emit('click')">

        <span v-html="component.label" />

        <span v-if="required" v-html="required" />
        
        <vl-form-info :component="component" />

        <vl-spinner-icon :loading="loading" />

    </label>

</template>

<script>

export default {
    props: ['component'],
    computed: {
        labelFullClass(){
            return 'vlFormLabel' + (this.labelClass ? ' '+this.labelClass : '')
        },
        required(){
            return this.component.$_data('required')
        },
        labelClass(){
            return this.component.$_data('labelClass')
        },
        noLabel(){
            return this.component.$_data('noLabel')
        },
        loading(){
            return this.component.$_state('loading')
        }
    }
}
</script>