<template>
    <vl-panel 
        v-show="!$_hidden" 
        v-bind="$_layoutWrapperAttributes"
        @includeObj="includeObject"
        @loaded="destroyPanel">

        <div v-if="komponents.length"><!-- had to wrap in a div for transition -->
            <template v-for="(row,index) in komponents">
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
            //this.$delete(this.komponents, 0)
            this.komponents = []
        },
        loadPanel(komponents){
            //this.komponents = this.komponents.concat(komponents) //TODO: append or replace komponents...
            this.komponents = komponents
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
        this.komponents = this.komponents || [] //when called in Vue directly (not through a PHP Form)
        
        this.loaded = this.komponents && this.komponents.length
    }
}
</script>
