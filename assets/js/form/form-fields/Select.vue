<template>
    <vl-form-field v-bind="$_wrapperAttributes">
        <vlTaggableInput 
            v-click-outside="blur"
            v-bind="$_taggableInputAttributes" 
            v-on="$_taggableInputEvents">
            <template v-slot:prepend v-if="prependIcon">
                <i :class="prependIcon"/>
            </template>
            <input
                class="vlFormControl"
                v-bind="$_attributes"
                v-on="$_events"
                v-model="inputValue"
                @keydown.enter.prevent="() => {}"
                ref="input"
                autocomplete="nope"
            />
            <template v-slot:append v-if="appendIcon">
                <i v-if="!$_readOnly" :class="appendIcon"/>
            </template>
        </vlTaggableInput>
        <div v-if="filteredOptions.length" class="vlOptions">
            <div v-for="(option,key) in filteredOptions" :key="key"
                class="vlOption"
                :class="{
                    'vlSelected' : isSelected(option),
                    'vlHoveredOption' : key == hoveredOption
                }"
                @click.stop="select(option)"
                @mouseover="setHoveredOption(key)">
                <vlCustomLabel :vcomponent="option.label" :kompoid="kompoid" />
            </div>
        </div>
        <div v-else class="vlOptions">
            <div class="vlOption" v-html="optionsMessage" />
        </div>

    </vl-form-field>
</template>

<script>
import Field from '../mixins/Field'
import HasTaggableInput from '../mixins/HasTaggableInput'

export default {
    mixins: [Field, HasTaggableInput],
    data(){
        return {
            optionsMessage: '',
            filteredOptions: [],
            hoveredOption: 0,
            updateForm: false
        }
    },
    mounted(){
        this.filteredOptions = this.options
        this.optionsMessage = this.ajaxOptions ? this.enterMoreCharacters : this.noOptionsFound
    },
    computed: {
        $_attributes() {
            return {
                ...this.$_defaultFieldAttributes
            }
        },
        $_events() {
            return {
                ...this.$_defaultFieldEvents,
                blur: () => {}, //do nothing
                change: () => {}, //do nothing
                click: this.loadOptions,
                keydown: this.keyDown,
            }
        },
        options(){ return this.component.options },
        prependIcon(){ return this.$_data('searchInput') ? 'icon-search' : null },
        appendIcon(){ return this.$_data('searchInput') ? null : 
            (this.$_state('focusedField') ? 'icon-up' : 'icon-down') },
        noOptionsFound(){ return this.$_data('noOptionsFound')},
        enterMoreCharacters(){ return this.$_data('enterMoreCharacters')},
        $_pristine() { return this.$_value.length === 0 },
        $_emptyValue() { return [] },
        ajaxOptions(){ return this.$_data('ajaxOptions') },
        ajaxOptionsMethod(){ return this.$_data('ajaxOptionsMethod') },
        ajaxMinSearchLength(){ return this.$_data('ajaxMinSearchLength') },
        ajaxOptionsFromField(){ return this.$_data('ajaxOptionsFromField') },
        debouncedAjaxFunction(){ return _.debounce(this.loadOptionsByAjax, 300)}
    },
    methods: {
        $_setInitialValue(){
            this.component.value = this.getOptionFromValue() || this.$_emptyValue
        },
        getOptionFromValue(){
            return this.$_multiple ? 
                _.map(this.$_value, (val) => {
                    var index = _.findIndex(this.options, (option) => { return val == option.value })
                    return index !== -1 && this.options[index]
                }): 
                _.filter(this.options, (o) => {return o.value == this.$_value} )
        },
        keyDown(key){
            if(key.code == 'Tab')
                this.$_blurAction()
        },
        $_keyUp(key){
            if(key.code == 'ArrowUp'){
                this.prevOption()
            }else if(key.code == 'ArrowDown'){
                this.nextOption()
            }else if(key.code === 'Enter'){
                this.selectHoveredOption()
            }else if(key.code == 'Escape'){
                this.forceBlur()
            }else{
                this.loadOptions()
            }
        },
        loadOptions(){
            if(this.$_readOnly)
                return

            if(this.ajaxOptions){
                if(this.ajaxOptionsFromField){
                    this.inputValue ? this.filterOptions() : this.debouncedAjaxFunction()
                }else{
                    this.debouncedAjaxFunction()
                }
            }else{
                this.filterOptions()
            }
        },
        $_fill(jsonFormData){
            if(this.$_multiple){
                !this.$_value.length ? 
                    jsonFormData[this.$_name] = [] :
                    this.$_value.forEach((option, k) => {
                        jsonFormData[this.$_name+'['+k+']'] = option.value
                    })
            }else{
                jsonFormData[this.$_name] = this.$_value.length ? this.$_value[0].value : ''
            }
        },
        blur(){
            this.$_state('focusedField') && this.forceBlur()
        },
        forceBlur(){
            this.reset()
            this.$refs.input.blur()
            this.$_blurAction()            
        },
        select(option){
            this.isSelected(option) ? this.$_remove(this.indexOf(option)) : this.add(option)
            this.reset()
            this.$_blurAction()
        },
        filterOptions(){
            this.filteredOptions = _.filter(this.options, (opt) => {
                var searchable = (_.isString(opt.label) ? opt.label : opt.label.label).toString().toLowerCase()
                return searchable.indexOf(this.inputValue.toString().toLowerCase()) !== -1
            })
        },
        add(option){
            if(this.$_multiple){
                this.component.value.push(option)
            }else{
                this.component.value = [option]
            }
            this.$_changeAction()
        },
        $_remove(index) {
            if(this.$_readOnly)
                return
            
            this.component.value.splice( index, 1)
            this.forceBlur()
            this.$_changeAction()
        },
        indexOf(option){
            return _.findIndex(this.component.value, {value: option.value})
        },
        isSelected(option){
            return this.indexOf(option) !== -1
        },
        reset(){
            this.$_emptyInput()
            if(this.ajaxOptions){
                this.component.options = []
                this.filteredOptions = []
            }
            this.hoveredOption = 0
        },
        prevOption(){
            this.hoveredOption = this.hoveredOption == 0 ? this.filteredOptions.length - 1 : this.hoveredOption - 1
        },
        nextOption(){
            this.hoveredOption = this.hoveredOption == this.filteredOptions.length - 1 ? 0 : this.hoveredOption + 1
        },
        setHoveredOption(key){
            this.hoveredOption = key
        },
        selectHoveredOption(){
            this.select(this.filteredOptions[this.hoveredOption])
        },
        loadOptionsByAjax(){

            if(this.ajaxOptionsFromField){
                
                this.$kompo.vlDeliverJsonFormData(this.kompoid, this.$_elementId())
                this.performAjax(this.$_getFromStore(this.ajaxOptionsFromField))

            }else{

                if(this.inputValue.length >= this.ajaxMinSearchLength){
                    this.performAjax()
                }else{
                    this.component.options = []
                    this.filteredOptions = []
                    this.optionsMessage = this.enterMoreCharacters
                }
            }
        },
        performAjax(search)
        {
            this.optionsMessage = '<i class="icon-spinner"/>'
            var initialSearch = search || this.inputValue
            axios({
                url: this.$_kompoRoute, 
                method: 'POST',
                data: {
                    method: this.ajaxOptionsMethod,
                    search: initialSearch
                },
                headers: {
                    'X-Kompo-Id': this.kompoid,
                    'X-Kompo-Action': 'search-options'
                }
            }).then(response => {
                if(!search && (initialSearch !== this.inputValue))
                    return
                this.component.options = response.data
                this.filteredOptions = response.data
                this.optionsMessage = this.noOptionsFound
            })
            .catch(e => this.$_handleAjaxError(e) )
        }
    }
}
</script>