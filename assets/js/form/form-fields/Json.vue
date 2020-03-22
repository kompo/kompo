<template>
    <vl-form-field v-bind="$_wrapperAttributes">
        <draggable 
            v-model="component.value" 
            handle=".js-row-move">

            <div 
                class="vlInputGroup" 
                v-for="(row, index) in component.value">
                <div class="vlInputPrepend" @click.stop="addNewRow(index)">
                    <i class="icon-plus"/>
                </div>
                <input
                    v-model="row[keyLabel]"
                    class="vlFormControl"
                    v-bind="$_attributes"
                    v-on="$_events"
                    :placeholder="firstPlaceholder(index)"
                    :id="$_elementId(index ? index : '')"
                />
                <input
                    v-model="row[valueLabel]"
                    class="vlFormControl"
                    v-bind="$_attributes"
                    v-on="$_events"
                    :placeholder="secondPlaceholder()"
                    :id="$_elementId('two'+index)"
                />
                <div v-if="$_value.length > 1" class="vlInputAppend js-row-move row-move">
                    <i class="icon-arrow-combo"/>
                </div>
                <div v-if="$_value.length > 1" class="vlInputAppend" @click.stop="deleteRow(index)">
                    <i class="icon-times"/>
                </div>
            </div>
        </draggable>
    </vl-form-field>
</template>

<script>
import draggable from 'vuedraggable'
import Field from '../mixins/Field'
import HasLists from '../mixins/HasLists'
import HasInputAttributes from '../mixins/HasInputAttributes'

export default {
    mixins: [Field, HasInputAttributes, HasLists],

    components: {
        draggable
    },

    computed:{

        valueLabel(){ return this.$_data('valueLabel')},

        $_attributes() {
            return {
                ...this.$_defaultFieldAttributes,
                ...this.$_defaultInputAttributes
            }
        },

        $_pristine() {
            if(!this.$_value)
                return true

            var pristine = true
            this.$_value.forEach((v) => {
                if(v[this.keyLabel] || v[this.valueLabel])
                    pristine = false
            })
            return pristine
        },
    },
    methods: {

        firstPlaceholder(index){ return (this.$_isFocused || index > 0) ? _.get(this.$_placeholder,'0') : '' },
        secondPlaceholder(){ return _.get(this.$_placeholder,'1') },

        fillValue(formData, name, value)
        {
            formData[name+'['+this.keyLabel+']'] = value[this.keyLabel]
            formData[name+'['+this.valueLabel+']'] = value[this.valueLabel]
        },

        getError(errors, k)
        {
            return errors[this.$_name+'.'+k+'.'+this.keyLabel] || errors[this.$_name+'.'+k+'.'+this.valueLabel]
        }
    }
}
</script>

