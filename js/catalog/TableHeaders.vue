<template>
    <thead>
        <tr>
            <template v-for="(col, i) in columns">
                <th v-if="colIsString(col)" v-html="col" />
                <component v-else v-bind="$_thAttributes(col, i)" />
            </template>
        </tr>
    </thead>
</template>

<script>
export default {
    props: {
        columns: {type: Array, required: true},
        vuravelid: {type: String, required: true}
    },
    methods:{
        $_thAttributes(col, index) { 
            return {
                key: col.id || index,
                index: parseInt(index),
                is: 'Vl' + col.component,
                vcomponent: col,
                vuravelid: this.vuravelid
            }
        },
        colIsString(col){
            return _.isString(col)
        }
    }
}

</script>
