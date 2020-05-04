<template>
    <tbody>
        <tr class="text-center">
            <td :colspan="headers.length" v-html="noItemsFound" />
        </tr>
    </tbody>
</template>

<script>
export default {
    props: {
        vkompo: {type: Object, required: true}
    },
    computed: {
    	noItemsFound(){ return this.vkompo.noItemsFound },
        headers(){ return this.vkompo.headers }
    }
}
</script>
