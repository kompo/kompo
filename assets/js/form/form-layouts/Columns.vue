<template>
    <div v-bind="$_layoutWrapperAttributes" v-show="!$_hidden">
        <div v-for="(col,index) in komponents"
            :key="index"
            :class="colClasses(col)">
            <component 
                v-bind="$_attributes(col)" />
        </div>
    </div>
</template>

<script>
import Layout from '../mixins/Layout'

export default {
    mixins: [Layout],
    computed:{
        $_customClassArray(){
            return [
                'row',
                this.$_data('alignClass'),
                this.$_data('guttersClass')
            ]
        },
        numOfColumns(){
            return this.komponents.length
        },
        equalColumnClass(){
            return 12 % this.numOfColumns == 0 ? 
                ('col-'+(this.breakpoint ? this.breakpoint+'-' : '')+parseInt(12/this.numOfColumns)) : 
                'col'
        },
        breakpoint(){
            return this.$_data('breakpoint')
        }
    },
    methods:{
        colClasses(col){
            return this.$_classString([
                this.colClass(col),
                this.$_vlMargins(col)
            ])
        },
        colClass(col){
            return this.$_data('col', col) || this.equalColumnClass
        }
    }
}
</script>
