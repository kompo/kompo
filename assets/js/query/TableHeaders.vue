<template>
    <thead>
        <tr>
            <template v-for="(th, i) in headers">
                <th v-if="isString(th)" v-html="th" />
                <component v-else v-bind="$_thAttributes(th, i)" />
            </template>
        </tr>
    </thead>
</template>

<script>
import HasVueComponent from '../element/mixins/HasVueComponent'

export default {
    mixins: [HasVueComponent],
    props: {
        headers: {type: Array, required: true},
        kompoid: {type: String, required: true}
    },
    methods:{
        $_thAttributes(th, index) { 
            return {
                key: th.id || index,
                index: parseInt(index),
                is: this.$_vueTag(th),
                vkompo: th,
                kompoid: this.kompoid
            }
        },
        isString(th){
            return _.isString(th)
        }
    }
}

</script>
