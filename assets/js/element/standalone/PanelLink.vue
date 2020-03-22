<template>
    <vl-link :vcomponent="{
        data: {
            loadsView: route ? true : false,
            panelId: panel || true
        },
        route: route
    }">

        <slot />

    </vl-link>
</template>


<script>
export default {
	props: ['route', 'panel'],
    mounted(){

    }
}
</script>
