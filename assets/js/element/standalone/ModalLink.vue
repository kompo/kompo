<template>
    <vl-link :vcomponent="{
        data: {
            btnOutlined: outlined || false
        },
        triggers: {
            click: [{
                action: 'axiosRequest',
                route: route,
                triggers: {
                    success: [{
                        action: 'fillModal',
                        modalName: modal || '',
                        warnBeforeClose: warnbeforeclose || false
                    }]
                }
            }]
        }
    }">

        <slot />

    </vl-link>
</template>


<script>
export default {
	props: ['route', 'modal', 'outlined', 'warnbeforeclose']
}
</script>
