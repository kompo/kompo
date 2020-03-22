<template>
    <div>
        <div class="table-responsive">
            <table class="vlTableForm table table-sm table-striped table-borderless" >
                <thead class="thead-dark">
                    <tr>
                        <th v-for="(col,index) in columns" :key="index" v-html="col" />
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, index) in items">
                        <component
                            v-bind="$_attributes(item, index)"
                        />
                    </template>
                </tbody>
            </table>
        </div>
        <div>
            <span @click.stop="addRow"><i class=" fa fa-plus" /> Add a row</span>
        </div>
    </div>
</template>

<script>
import Layout from '../mixins/Layout'

export default {
    mixins: [Layout],
    data: () => ({
        columns: []
    }),
    created(){
        this.columns = this.vcomponent.columns
    },
    methods:{
        addRow(){
            //TODO
        }
    }
}
</script>
