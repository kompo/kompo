export default {
    methods: {
        $_vlEmitFrom(event, payload){ 
            this.$vuravel.vlEmitFrom(this.vuravelid, event, payload)
        },
        $_vlOn(event, func){
            this.$vuravel.events.$on(event, func)
        },
        $_vlOff(event){
            this.$vuravel.events.$off(event)
        }
        
    }
}
