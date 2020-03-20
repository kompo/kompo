<template>
    <vl-form-field v-bind="$_wrapperAttributes">
        <vlLocaleTabs 
            :locales="locales" 
            :activeLocale="activeLocale"
            @changeLocale="changeTab"/>
        <ckeditor 
            class="vlFormControl"
            ref="content"
            v-model="currentTranslation" 
            v-bind="$_attributes"
            v-on="$_events"
            @keydown.stop
            
            :editor="editor" 
            :config="editorConfig" />
    </vl-form-field>
</template>

<script>
import Field from 'vuravel-form/js/mixins/Field'
import Translatable from 'vuravel-form/js/mixins/Translatable'
import CKEditor from '../mixins/CKEditor'

export default {
    mixins: [Field, CKEditor, Translatable]
}
</script>

