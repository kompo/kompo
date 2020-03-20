<template>

    <transition name="modal">

        <div class="vlModalMask" ref="modal" 
            tabindex="0"
            @keydown.left="previous"
            @keydown.right="next"
            @mousedown="mouseDown" 
            @mouseup="mouseUp" 
             v-if="opened" 
            :style="{'z-index': zIndex - 2 }">

            <div class="vlModalClose" 
                @click.stop="closeAction" 
                :style="{'z-index': zIndex + 2 }">
              <i class="icon-times-circle"></i>
            </div>

            <i v-if="arrows" class="vlModalBtn icon-chevron-left" 
                @click.stop="previous" />

            <i v-if="arrows" class="vlModalBtn icon-chevron-right" 
                @click.stop="next" />

            <div class="vlModalWrapper">

                <div 
                    class="vlModalContainer" 
                    :id="'vlModalContainer'+name"
                    :style="{'width': width}">

                    <!-- v-if necessary -->
                    <vl-panel 
                        v-if="panelId" 
                        v-show="ajaxContent"
                        :id="panelId" />
                
                    <slot />

                </div>
                
            </div>

        </div>
        
    </transition>
</template>

<script>
    export default {
        props: ['name', 'width', 'warn', 'arrows'],
        data(){
            return {
                readyToClose: false,
                opened : false,
                ajaxContent: false,
                zIndex: 2000,
                panelId: '',
                warnData: false
            }
        },
        computed: {
            warnbeforeclose(){ return this.warn || this.warnData }
        },
        methods:{
            outsideModal(e){
                return !$(e.target).hasClass('vlModalContainer') 
                    && !$(e.target).parents('#vlModalContainer'+this.name).length
            },
            warnConfirmation(){ 
                return !this.warnbeforeclose || (this.warnbeforeclose && confirm(this.warnbeforeclose))
            },
            mouseDown(e){
                if (this.outsideModal(e))
                    this.readyToClose = true

                e.stopPropagation() //so that parent modals don't close too
            },
            mouseUp(e){
                if (this.outsideModal(e) && this.readyToClose)
                    if(this.warnConfirmation()){
                        this.closeAction()
                    }else{
                        this.readyToClose = false
                    }

                e.stopPropagation() //so that parent modals don't close too
            },
            close: function(e) {
                if (!$(e.target).hasClass('vlModalContainer') 
                    && !$(e.target).parents('#vlModalContainer'+this.name).length){

                    if(!this.warnbeforeclose || (this.warnbeforeclose && confirm(this.warnbeforeclose))){    
                        this.closeAction()
                    }

                    e.stopPropagation() //so that parent modals don't close too
                }
            },
            closeAction(){
                this.opened = false
                this.$emit('closed')
            },
            open(ajaxContent){
                this.opened = true
                this.readyToClose = false
                this.ajaxContent = ajaxContent ? true : false
                this.$emit('opened')
                //applies zIndex to the vlModalClose higher if in another modal
                this.$nextTick(()=> {
                    var currentElem = $(this.$refs.modal), depth = 0
                    while(currentElem.closest('.vlModalWrapper').length){
                        depth += 1
                        currentElem = currentElem.closest('.vlModalWrapper').eq(0).parent()
                    }
                    this.zIndex += depth*100
                })
            },
            next(){
                if(this.arrows)
                    this.$emit('next')
            },
            previous(){
                if(this.arrows)
                    this.$emit('previous')
            }
        },
        created(){
            this.panelId = this.name !='default' ? this.name : 'vlDefaultModal'
        },
        mounted(){
            this.$modal.events.$on('show', (modalName, ajaxContent, warnbeforeclose) => {
                if(modalName == this.name){
                    this.warnData = warnbeforeclose || false
                    this.open(ajaxContent)
                    if(this.$refs.modal)
                        this.$nextTick(() => this.$refs.modal.focus()) //to be able to use keydown events
                }
            })
            this.$modal.events.$on('close', (modalName) => {
                if(modalName == this.name)
                    this.closeAction()
            })

            this.$modal.events.$on('showFill'+this.name, (html) => {
                this.open(true)
                this.$nextTick(()=> {
                    this.$vuravel.vlFillPanel(this.panelId, html)
                })
            })
        }
    }
</script>
