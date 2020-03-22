<template>
    <div class="vlEditableTextareaWrapper">
        
        <div 
            class="vlFormField vlEditableText cursor-pointer" 
            v-if="!editing" 
            v-html="$_value || $_label" 
            @click.stop="startEditing"/>
        
        <vl-form-field v-if="editing" v-bind="$_wrapperAttributes">
            <textarea
                v-click-outside="saveAndClose"
                v-model="component.value"
                class="vlFormControl"
                ref="content"
                v-bind="$_attributes"
                v-on="$_events" />
        </vl-form-field>
        
    </div>
</template>

<script>
import Field from '../mixins/Field'
export default {
    mixins: [Field],
    data(){
        return {
            editing: false
        }
    },
    computed: {
        $_attributes(){
            return {
                ...this.$_defaultFieldAttributes,
                rows: this.rows
            }
        },
        rows(){
            return this.$_data('rows') || 
                Math.min(3, this.$_value ? (parseInt(this.$_value.toString().length/20) + 1) : 2 )
        }
    },
    methods: {
        startEditing(){
            this.editing = true
            this.$nextTick( () => {this.$refs.content.focus()})
        },
        saveAndClose(){
            this.editing = !this.editing
        }
    }
}
</script>

