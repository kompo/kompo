<template>
    <div v-if="open" class="vlAlert" :class="alertclass">
        <i v-if="iconclass" :class="iconclass" />
        <div v-html="message"/>
        <div class="vlAlertClose" @click.stop="close()">
          <i class="icon-times vlCursorPointer"></i>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            message: {type: String, required: true},
            iconclass: {type: String},
            alertclass: {type: String},
            index: {type: Number}
        },
        data(){
            return {
                open : true
            }
        },
        methods:{
            close(){
                this.open = false
            }
        }
    }
</script>
