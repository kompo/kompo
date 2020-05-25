<template>
    <tr :class="$_activeClass">
        <template v-for="(component,k) in $_props">
            <td :key="k" v-if="$_vueTag(component)" >
                <component 
                	v-bind="componentAttributes(component)"
                    @activate="activate" />
            </td>
        </template>
    </tr>
</template>

<script>
import Card from '../mixins/Card'

export default {
    mixins: [Card]
}
</script>
