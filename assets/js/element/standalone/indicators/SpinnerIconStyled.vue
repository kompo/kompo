<template>
    <div v-if="loading" class="vlSpinnerStyled">

        <vl-spinner-icon :loading="loading"/>
            
    </div>
</template>

<script>
export default {
    props: ['loading']
}
</script>
