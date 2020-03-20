<template>
    <vl-form-field v-bind="$_wrapperAttributes" @labelclick="focus">
        <trix-editor
            ref="trixEditor" 
            class="vlFormControl trix-content"
            
            v-bind="$_attributes"

            @trix-initialize="initialize"
            @keydown.stop
            v-on="$_events"
        />
    </vl-form-field>
</template>

<script>
import Field from 'vuravel-form/js/mixins/Field'

import Trix from 'trix'
import 'trix/dist/trix.css'

export default {
    mixins: [Field],
    components: { Trix },
    computed: {
        $_events() {
            return {
                ...this.$_defaultFieldEvents,
                'trix-change': this.setValue
            }
        }
    },

    methods: {
        focus(){
            this.$_focusAction()
            this.$nextTick(() => { this.$refs.trixEditor.focus() })
        },
        initialize() {
            if(this.$_value)
                this.$refs.trixEditor.editor.insertHTML(this.$_value)
        },
        setValue(value) {
            this.component.value = this.$refs.trixEditor.value
            this.$_changeAction()
        },
        
    },
}
</script>

