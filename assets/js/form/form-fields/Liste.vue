<template>
    <vl-form-field v-bind="$_wrapperAttributes">
        <draggable 
            v-model="component.value" 
            handle=".js-row-move">

          <div class="vlInputGroup" v-for="(row, index) in component.value">
            <div class="vlInputPrepend" @click.stop="addNewRow(index)">
                <i class="icon-plus"/>
            </div>
            <input
                v-model="row[keyLabel]"
                class="vlFormControl"
                v-bind="$_attributes"
                v-on="$_events"
                :placeholder="placeholder(index)"
                :id="$_elementId(index)"
            />
            <div v-if="$_value.length > 1" class="vlInputAppend js-row-move row-move">
                <i class="icon-arrow-combo"/>
            </div>
            <div v-if="$_value.length > 1"  class="vlInputAppend" @click.stop="deleteRow(index)">
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
                if(v[this.keyLabel])
                    pristine = false
            })
            return pristine
        },
    },
    methods: {

        placeholder(index){ return (this.$_isFocused || index > 0) ? this.$_placeholder : '' },

        fillValue(formData, name, value)
        {
            formData[name+'['+this.keyLabel+']'] = value[this.keyLabel]
        },

        getError(errors, k)
        {
            return errors[this.$_name+'.'+k+'.'+this.keyLabel]
        }

    }
}
</script>

