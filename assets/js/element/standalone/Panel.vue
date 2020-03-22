<template>
    
    <div :id="id" class="vlPanel">
        
        <transition :name="usedTransition" :mode="usedMode">
            <slot />
            <div v-if="html" :is="{template: html}" />
            <component 
                v-if="partial" :is="partial" 
                :vcomponent="component" 
                :key="panelKey" />
        </transition>
        
    </div>

</template>

<script>
export default {
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

        this.$kompo.events.$on('vlFillPanel' + this.id, (response, parentTrigger) => {
            this.component = null
            this.html = null
            this.partial = null

            if(parentTrigger && parentTrigger.included)
                return this.$emit('includeObj', response) //emit and stop

            if(!_.isString(response)){
                this.partial = response.partial
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
