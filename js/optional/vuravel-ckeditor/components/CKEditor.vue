<template>
    <vl-form-field v-bind="$_wrapperAttributes" @labelclick="focus">
        <ckeditor 
            class="vlFormControl"
            ref="content"
            v-model="component.value" 
            v-bind="$_attributes"
            v-on="$_events"
            @keydown.stop
            
            :editor="editor" 
            :config="editorConfig" />
    </vl-form-field>
</template>

<script>
import Field from 'vuravel-form/js/mixins/Field'
import CKEditor from '../mixins/CKEditor'

export default {
    mixins: [Field, CKEditor]
}
</script>

