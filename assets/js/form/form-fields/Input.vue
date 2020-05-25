<template>
    <vl-form-field v-bind="$_wrapperAttributes">
        <div v-if="$_icon" class="vlInputGroup">
            <div class="vlInputPrepend" @click="focus">
                <i :class="$_icon"/>
            </div>
            <input
                v-model="component.value"
                class="vlFormControl"
                v-bind="$_attributes"
                v-on="$_events"
                ref="input"
            />
        </div>
        <input
            v-if="!$_icon && !$_rIcon"
            v-model="component.value"
            class="vlFormControl"
            v-bind="$_attributes"
            v-on="$_events"
            ref="input"
        />
        <div v-if="$_rIcon" class="vlInputGroup">
            <input
                v-model="component.value"
                class="vlFormControl"
                v-bind="$_attributes"
                v-on="$_events"
                ref="input"
            />
            <div class="vlInputAppend" @click="focus">
                <i :class="$_rIcon"/>
            </div>
        </div>
    </vl-form-field>
</template>

<script>
import Field from '../mixins/Field'
import HasInputAttributes from '../mixins/HasInputAttributes'

export default {
    mixins: [Field, HasInputAttributes],
    computed: {
        $_attributes() {
            return {
                ...this.$_defaultFieldAttributes,
                ...this.$_defaultInputAttributes
            }
        }
    },
    methods: {
        focus(){
            this.$refs.input.focus()
        }
    }
}
</script>

