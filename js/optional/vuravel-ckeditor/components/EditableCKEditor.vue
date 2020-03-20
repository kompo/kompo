<template>
    <div class="vlEditableWrapper">
        <div 
            class="vlEditableContent cursor-pointer" 
            v-if="!editing" 
            v-html="$_value || $_label" 
            @click.stop="startEditing"/>
        
        <VlCKEditor
            v-if="editing" 
            :vcomponent="component"
            v-click-outside="close"
            @change="change"/>
    </div>
</template>

<script>
import Field from 'vuravel-form/js/mixins/Field'
export default {
    mixins: [Field],
    data(){
        return {
            editing: false
        }
    },
    computed: {

    },
    methods: {
        startEditing(){
            this.editing = true
        },
        close(){
            this.editing = !this.editing
        },
        change(value){
            this.component.value = value
        }
    }
}
</script>

