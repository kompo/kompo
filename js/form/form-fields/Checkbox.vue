<template>
    <vl-form-field v-bind="$_wrapperAttributes" @labelclick="changed">
        <input
            v-model="component.value"
            type="checkbox"
            role="checkbox"
            v-bind="$_attributes"
        />
        <div v-on="$_events" class="vlToggleArea">
            <i class='icon-check'></i>
        </div>
    </vl-form-field>
</template>

<script>
import FieldCheckbox from '../mixins/FieldCheckbox'
export default {
    mixins: [FieldCheckbox]
}
</script>