<template>
    <i v-if="loading" class="icon-spinner"/>
</template>

<script>
export default {
    props: ['loading']
}
</script>
