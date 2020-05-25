<template>
    <div>
        <div class="table-responsive">
            <table class="vlTableForm table table-sm table-striped table-borderless" >
                <thead class="thead-dark">
                    <tr>
                        <th v-for="(th,index) in headers" :key="index" v-html="th" />
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
        headers: []
    }),
    created(){
        this.headers = this.vkompo.headers
    },
    methods:{
        addRow(){
            //TODO
        }
    }
}
</script>
