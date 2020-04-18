<template>
    <vl-button :vkompo="{
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

    </vl-button>
</template>


<script>
export default {
    props: ['route', 'modal', 'outlined', 'warnbeforeclose']
}
</script>
