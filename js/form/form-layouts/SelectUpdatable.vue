<template>
    <div v-bind="$_layoutWrapperAttributes" v-show="!$_hidden">
        
        <component v-bind="$_attributes(components[0])" :key="renderKey" />
        
        <div>
            <a class="vlFormComment" href="javascript:void(0)" @click.stop="loadUpdateForm">
                <i class="icon-plus" /><span v-html="$_data('updateOptionsLabel')"/>
            </a>
            <vl-modal :name="'modal'+$_elementId()">
                <vl-form :vcomponent="updateForm" @success="updateOptionsAndValue"/>
            </vl-modal>
        </div>

    </div>
</template>

<script>
import Layout from '../mixins/Layout'
import Field from '../mixins/Field' //not used as a mixin, just for $_name

export default {
    mixins: [Layout],
    data(){
        return {
            updateForm: false,
            renderKey: 0
        }
    },
    methods: {
        loadUpdateForm(){
            axios({
                url: this.$_route, 
                method: 'POST',
                data: this.$_ajaxPayload,
                headers: {
                    'X-Vuravel-Id': this.vuravelid
                }
            }).then(r => {
                this.updateForm = r.data
                this.$modal.show('modal'+this.$_elementId())
            }).catch(e => this.$_handleAjaxError(e) )
        },
        updateOptionsAndValue(response){
            var relatedValue = response.status == 202 ? 
                                    response.data.form.record.record : 
                                    response.data

            if(this.$_data('ajaxOptionsRoute')){
                var newLabel = relatedValue[this.$_data('optionsLabel')] //!! does not work if label is array
                var newKey = relatedValue[this.$_data('optionsKey')]
                this.updateComponentOptionsAndValue([{value: newKey, label: newLabel}], relatedValue)
            }else{
                axios({
                    url: this.$_data('updateOptionsRoute'), 
                    method: 'POST',
                    data: {
                        selectName: Field.computed.$_name.call(this)
                    },
                    headers: {
                        'X-Vuravel-Id': this.vuravelid
                    }
                }).then(response => {
                    this.updateComponentOptionsAndValue(response.data, relatedValue)
                }).catch(e => this.$_handleAjaxError(e) )
            }
        },
        updateComponentOptionsAndValue(newOptions, relatedValue)
        {
            var newSelect = this.components[0]
            newSelect.options = newOptions
            newSelect.value = relatedValue[this.$_data('optionsKey')]
            this.components.splice(0, 1, newSelect)
            this.renderKey += 1
            if(!this.$_data('keepModalOpen'))
                this.$modal.close('modal'+this.$_elementId())
        }
    }
}
</script>