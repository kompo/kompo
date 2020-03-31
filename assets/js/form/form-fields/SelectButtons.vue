<template>
    <vl-form-field v-bind="$_wrapperAttributes">

        <div :class="containerClass" v-bind="$_attributes">

            <div :class="optionClass" @click.stop="setValue(key)"
                v-for="(option,key) in options" :key="componentKey(key)">

                <vlCustomLabel 
                    tabindex="0"
                     v-on="$_events"
                    :vcomponent="option.label" 
                    :kompoid="kompoid" 
                    :class="optionInnerClass(option, key)" />

            </div>

        </div>

    </vl-form-field>
</template>

<script>
import Field from '../mixins/Field'

export default {
    mixins: [Field],
    computed:{
        containerClass(){ return this.$_data('containerClass') },
        optionClass(){ return this.$_data('optionClass') },
        options(){ return this.component.options }
    },
    methods: {
        optionInnerClass(option, key){ 
            return this.$_data('optionInnerClass') +
                (option.selected ? ' vlSelected' : '') 
        },
        $_setInitialValue(){
            this.component.options.forEach((option, key) => {
                option.selected = option.value == this.component.value ? true : false
            })
        },
        componentKey(key){ return this.$_elKompoId + key },
        setValue(selectedKey) {
            var oldValue = this.component.value
            this.component.value = null
            this.component.options = _.map(this.component.options, (opt, key) => {
                opt.selected = key == selectedKey && oldValue != opt.value ? true : false
                if(opt.selected)
                    this.component.value = opt.value
                return opt
            })

            this.$_focusAction()
            this.$_changeAction()
            this.$_blurAction()

        },
        $_resetSortValue(){
            this.component.value = null
            this.component.options = _.map(this.component.options, (opt, key) => {
                opt.selected = false
                return opt
            })
        },
    }
}
</script>

