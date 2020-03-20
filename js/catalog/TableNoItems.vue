<template>
    <tbody>
        <tr class="text-center">
            <td :colspan="columns.length" v-html="noItemsFound" />
        </tr>
    </tbody>
</template>

<script>
export default {
    props: {
        columns: {type: Array, required: true},
        noItemsFound: {type: String, required: true}
    }
}
</script>
