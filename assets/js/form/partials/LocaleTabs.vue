<template>
    <div class="vlLocales">
        <a :class="localeKey === activeLocale ? 'vlSelected' : ''" 
            :key="`a-${localeKey}`" 
            v-for="(locale, localeKey) in locales"
            @click.stop="changeLocale(localeKey)">
            {{ locale }}
        </a>

        <a v-if="noLocales" href="javascript:void(0)">No locales defined</a>
    </div>
</template>

<script>
export default {
    props: {
        activeLocale: {type: String, required: true},
        locales: {type: Object | Array, required: true }
    },
    computed: {
        noLocales(){
            return _.isEmpty(this.locales)
        }
    },
    methods: {
        changeLocale(localeKey){
            this.$emit('changeLocale', localeKey)
        }
    }
}
</script>