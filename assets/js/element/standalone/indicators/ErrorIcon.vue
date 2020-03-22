<template>
    <i v-if="error" class="icon-times"/>
</template>

<script>
export default {
    props: ['error']
}
</script>
