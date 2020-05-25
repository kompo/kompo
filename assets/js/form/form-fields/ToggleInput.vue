<template>
    <vl-form-field v-bind="$_wrapperAttributes" @labelclick="changed">
        <input
            v-model="toggleChecked"
            type="checkbox"
            role="checkbox"
            v-bind="$_attributes"
        />
        <div v-on="$_events" class="vlToggleArea" />
        <template v-slot:after>
            <div v-show="toggleChecked" class="vlInputWrapper vlOptionalInput">
                <input
                    v-model="component.value"
                    :placeholder="$_placeholder"
                    ref="optionalInput"
                    class="vlFormControl"
                />
            </div>
        </template>
    </vl-form-field>
</template>

<script>
import Field from '../mixins/Field'
import FieldCheckbox from '../mixins/FieldCheckbox'
export default {
    mixins: [Field, FieldCheckbox],
    data(){
        return {
            toggleChecked: false
        }
    },
    computed: {
        $_attributes(){
            return {
                ...this.$_defaultFieldAttributes,
                'aria-checked': this.checked
            }
        },
        checked() {
            return this.toggleChecked
        }
    },
    watch: {
        toggleChecked: function(val){
            if(val)
                setTimeout( () => {this.$refs.optionalInput.focus()}, 100)
        }
    },
    methods: {
        handleToggle(){
            this.toggleChecked = !this.checked
        },
        $_fill(jsonFormData){
            jsonFormData[this.$_name] = this.checked ? this.$_value : ''
        }
    },
    mounted(){
        if(this.$_value)
            this.toggleChecked = true
    }
}
</script>