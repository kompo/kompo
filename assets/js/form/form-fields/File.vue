<template>
    <vl-form-field v-bind="$_wrapperAttributes">
        <vlTaggableInput 
            v-bind="$_taggableInputAttributes" 
            v-on="$_taggableInputEvents"
            :labelKey="labelKey">
            <input
                class="vlFormControl" 
                v-bind="$_attributes"
                v-on="$_events"
                type="file"
                ref="input" />
            <template v-slot:append>
                <i class="icon-upload"/>
            </template>
        </vlTaggableInput>
    </vl-form-field>
</template>

<script>
import FieldFile from '../mixins/FieldFile'
import HasTaggableInput from '../mixins/HasTaggableInput'

export default {
    mixins: [FieldFile, HasTaggableInput],
    data(){
        return {
            labelKey: 'name'
        }
    },
    computed:{
        placeholder(){
            return this.$_pristine ? 
                    'Upload ' + (_.isNumber(this.$_multiple) ? this.$_multiple : '') + 
                        ' file'+ (this.$_multiple ? 's' : '') :
                    ''
        }
    },
    methods: {
        addFile(){
            this.$_addRefFiles()
            this.$_blurActionDelayed()
            this.$_changeAction()
        },
        uploadFiles(){
            this.$refs.input.click()
        }
    }
}
</script>