<template>
    
    <div :id="id" class="vlPanel">
        
        <transition :name="usedTransition" :mode="usedMode">
            <slot />
            <div v-if="html" :is="{template: html}" />
            <component 
                v-if="partial" :is="partial" 
                :vkompo="component" 
                :key="panelKey" />
        </transition>
        
    </div>

</template>

<script>
import HasVueComponent from '../mixins/HasVueComponent'

export default {
    mixins: [HasVueComponent],
    props: {
        id: { type: String, required: true },
        transition: { type: String },
        mode: { type: String }
    },
    data(){
        return {
            html : null,
            component: null,
            partial: null,
            panelKey: 0,
            usedTransition: null,
            usedMode: null
        }
    },
    created(){

        this.usedTransition = this.transition || 'fadeIn'
        this.usedMode = this.mode || (this.usedTransition == 'fadeIn' ? 'out-in' : '')

        this.$kompo.events.$on('vlFillPanel' + this.id, (response, included) => {
            this.component = null
            this.html = null
            this.partial = null

            if(included)
                return this.$emit('includeObj', response) //emit and stop

            if(!_.isString(response)){
                this.partial = this.$_vueComponent(response) == 'FormQuery' ? 'vl-query' : 'vl-form'
                this.component = response
                this.panelKey += 1
            }else{
                this.html = response
            }
            this.$emit('loaded')
        })

    }
}
</script>
