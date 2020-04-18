<template>
    <tr>
        <td v-for="(item,k) in item" :key="k" >
            <vl-form :vkompo="item" />
        </td>
        <td><i class="fa fa-trash" /></td>
    </tr>
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card]
}
</script>
