export default {
    methods: {
        $_vlEmitFrom(event, payload){ 
            this.$kompo.vlEmitFrom(this.kompoid, event, payload)
        },
        $_vlOn(event, func){
            this.$kompo.events.$on(event, func)
        },
        $_vlOff(event){
            this.$kompo.events.$off(event)
        }
    }
}
