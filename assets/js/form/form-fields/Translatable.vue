<template>
    <vl-form-field v-bind="$_wrapperAttributes">
        <vlLocaleTabs 
            :locales="locales" 
            :activeLocale="activeLocale"
            @changeLocale="changeTab"/>
        <textarea
            v-model="currentTranslation"
            class="vlFormControl"
            ref="content"

            v-bind="$_attributes"
            v-on="$_events"
        />
    </vl-form-field>
</template>

<script>
import Field from '../mixins/Field'
import Translatable from '../mixins/Translatable'
export default {
    mixins: [Field, Translatable]
}
</script>

