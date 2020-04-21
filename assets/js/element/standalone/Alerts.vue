<template>

    <transition name="alert">

        <div class="vlAlertWrapper" v-if="alerts.length" 
            :style="{'z-index': zIndex - 2 }">

            <vl-alert v-for="(alert, key) in alerts"
                :message="alert.message"
                :iconclass="alert.iconClass"
                :alertclass="alert.alertClass"
                :key="key" />

        </div>
        
    </transition>
</template>

<script>
    export default {
        data(){
            return {
                alerts : [],
                zIndex: 2500, //higher than Modal's 2000
                initialId: 0
            }
        },
        methods:{
            close(k){
                this.alerts.splice(k)
            },
            closeById(id){
                var indexWithId = _.findIndex(this.alerts, (alert) => alert.id == id)
                this.alerts.splice(indexWithId)
            },
            addAlert(alert){
                this.initialId += 1
                
                this.alerts.push(Object.assign(alert, {
                    id: this.initialId
                }))
                setTimeout(()=> this.closeById(this.initialId), 3000)
            }
        },
        mounted(){
            this.$modal.events.$on('showAlert', (alert) => {
                this.addAlert(alert)
            })
        }
    }
</script>
