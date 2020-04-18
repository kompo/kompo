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
        headers: {type: Array, required: true},
        noItemsFound: {type: String, required: true}
    }
}
</script>
