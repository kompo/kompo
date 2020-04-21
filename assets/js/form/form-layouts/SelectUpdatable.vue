<template>
    <div v-bind="$_layoutWrapperAttributes" v-show="!$_hidden">
        
        <component v-bind="$_attributes(komponents[0])" :key="renderKey" />
        
        <div>
            <a class="vlFormComment" href="javascript:void(0)" @click.stop="loadUpdateForm">
                <i class="icon-plus" /><span v-html="$_data('updateOptionsLabel')"/>
            </a>
            <vl-modal :name="'modal'+$_elKompoId">
                <vl-form :vkompo="updateForm" @success="updateOptionsAndValue"/>
            </vl-modal>
        </div>

    </div>
</template>

<script>

import Layout from '../mixins/Layout'
import DoesAxiosRequests from '../mixins/DoesAxiosRequests'

export default {
    mixins: [Layout, DoesAxiosRequests],
    data(){
        return {
            updateForm: false,
            renderKey: 0
        }
    },
    methods: {
        loadUpdateForm(){

            this.$_kAxios.$_loadKomposer().then(r => {

                this.updateForm = r.data
                this.$modal.show('modal'+this.$_elKompoId)

            })

        },
        updateOptionsAndValue(r){

            var relatedValue = r.status == 202 ? r.data.form.model : r.data

            if(this.$_data('ajaxOptions')){
                
                var newLabel = relatedValue[this.$_data('optionsLabel')] //!! does not work if label is array
                var newKey = relatedValue[this.$_data('optionsKey')]
                this.updateComponentOptionsAndValue([{value: newKey, label: newLabel}], relatedValue)

            }else{

                this.$_kAxios.$_updatedOption().then(response => {

                    this.updateComponentOptionsAndValue(response.data, relatedValue)

                })
            }
        },
        updateComponentOptionsAndValue(newOptions, relatedValue)
        {
            var newSelect = this.komponents[0]
            newSelect.options = newOptions
            newSelect.value = relatedValue[this.$_data('optionsKey')]
            this.komponents.splice(0, 1, newSelect)
            this.renderKey += 1

            if(!this.$_data('keepModalOpen'))
                this.$modal.close('modal'+this.$_elKompoId)
        }
    }
}
</script>