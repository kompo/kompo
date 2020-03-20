<template>

    <div v-if="errorText" class="vlFieldErrors" v-html="errorText" />

</template>

<script>

export default {
    props: ['errors'],
    
    computed: {
        errorText() {
            if (this.errors && this.errors.length) 
                return this.errors.join('<br>')
        }
    }
}
</script>
