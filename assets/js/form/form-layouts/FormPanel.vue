<template>
    <vl-panel 
        v-show="!$_hidden" 
        v-bind="$_layoutWrapperAttributes"
        @includeObj="includeObject"
        @loaded="destroyPanel">

        <div v-if="components.length"><!-- had to wrap in a div for transition -->
            <template v-for="(row,index) in components">
                <component v-bind="$_attributes(row)" />
            </template>
        </div>


    </vl-panel>
</template>

<script>
import Layout from '../mixins/Layout'
export default {
    mixins: [Layout],
    computed:{
        $_customLayoutAttributes(){
            return {
                transition: this.$_data('transition'),
                mode: this.$_data('transitionMode')
            }
        },
        hidesOnLoad(){
            return this.component.$_data('hidesOnLoad') // this.$_data(...) not working... ??
        }
    },
    methods:{
        destroyPanel(){
            //this.$delete(this.components, 0)
            this.components = []
        },
        loadPanel(components){
            //this.components = this.components.concat(components) //TODO: append or replace components...
            this.components = components
        },
        revertPanel(){
            this.$_togglesForm(this.hidesOnLoad)
            this.destroyPanel()
        },
        includeObject(object){
            this.destroyPanel()
            this.$nextTick( () => {
                this.loadPanel(object)
                this.$nextTick( () => { this.$_togglesForm(this.hidesOnLoad) })
            })
        }
    },

    created() {
        this.components = this.components || [] //when called in Vue directly (not through a PHP Form)
        
        this.loaded = this.component.components && this.component.components.length
    }
}
</script>
