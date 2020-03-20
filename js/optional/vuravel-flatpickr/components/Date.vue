<template>
    <vl-form-field v-bind="$_wrapperAttributes" @labelclick="focus">

        <div class="vlInputGroup">
            <div class="vlInputPrepend" @click.stop="focus">
                <i :class="$_icon" />
            </div>
            <flat-pickr 
                v-model="component.value"                                                    
                class="vlFormControl"
                ref="flatpickr"
                v-bind="$_attributes"
                v-on="$_events" 
                autocomplete="off" />
            <div class="vlInputAppend">
                <i v-if="$_value && !$_readOnly" class="icon-times" @click.stop="clear" />
            </div>
        </div>

    </vl-form-field>
</template>

<script>
import Field from 'vuravel-form/js/mixins/Field'

import flatPickr from 'vue-flatpickr-component'; 
import 'flatpickr/dist/flatpickr.css';
import 'flatpickr/dist/themes/airbnb.css';

export default {
    mixins: [Field],
    components: { flatPickr },
    computed: {
        $_enableTime(){ return this.$_data('enableTime') || false },
        $_noCalendar(){ return this.$_data('noCalendar') || false },
        $_attributes(){
            return {
                ...this.$_defaultFieldAttributes,
                config: {
                    wrap: true,
                    dateFormat: this.$_data('dateFormat'),
                    enableTime: this.$_enableTime,
                    noCalendar: this.$_noCalendar,
                    allowInput: true,
                    altInput: true,
                    altInputClass: 'vlFormControl',
                    altFormat: this.$_data('altFormat'),
                    time_24hr: true
                },
                disabled: this.$_readOnly ? true : false
            }
        },
        $_events() { 
            return {
                ...this.$_defaultFieldEvents,
                change: () => {},
                'on-change': this.change,
                'on-open': this.focus,
                'on-close': this.persistDate
            }
        }
    },
    methods:{
        //useful methods if needed
        //this.$refs.flatpickr.fp.open()
        //this.$refs.flatpickr.fp._input.focus()
        //this.$refs.flatpickr.fp.close()
        focus(){
            this.$refs.flatpickr.fp.showTimeInput = true //fix to show time in datetime
            if(this.$_noCalendar) //for Time, we want it to focus to the hour input
                setTimeout( () => this.$refs.flatpickr.fp.hourElement.focus(), 50)
        },
        change(obj,value) {
            this.$_clearErrors()
        },
        clear(){
            this.component.value = ''
            this.$_blurAction()
            this.$_changeAction()
        },
        persistDate(obj, value){
            /*
            if(value){
                //this.component.value = value
                //this.$_changeAction()
                //this.key += 1
            }
            this.$_blurAction()*/
        }
    }
}
</script>

