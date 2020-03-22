<template>
    <div v-if="open" :class="alert.class">
        <div v-html="alert.message"/>
        <div class="vlAlertClose" @click.stop="close()">
          <i class="icon-times-circle"></i>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            alert: {type: Object, required: true},
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
