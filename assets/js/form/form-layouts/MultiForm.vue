<template>
    <div v-bind="$_layoutWrapperAttributes" v-show="!$_hidden">
        <vl-form-label :component="component" />
        <draggable 
            v-model="komponents" 
            handle=".js-row-move"
            :style="$_elementStyles">
            <vl-rows
                v-for="(comp,index) in komponents"
                :vkompo="comp"
                :key="index"
                v-bind="$_attributes(comp)" />
        </draggable>
        <a href="javascript:void(0)" @click.stop="addRow">
            <i class=" fa fa-plus" /> Add a row
        </a>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
import Layout from '../mixins/Layout'
import HasName from '../mixins/HasName'
import DoesAxiosRequests from '../mixins/DoesAxiosRequests'

export default {
    mixins: [Layout, HasName, DoesAxiosRequests],
    components: { draggable },
    methods:{
        addRow(){
            this.$_kAxios.$_loadKomposer().then(r => {

                this.komponents.push(r.data)

            })
        },
        $_validate(errors) {

            var ownErrors = _.pickBy(errors, (value, key) => {
                return _.startsWith(key, this.$_name+'.')
            })

            this.komponents.forEach( (form,k) => {
                var formErrors = _.mapKeys(_.pickBy(ownErrors, (value, key) => {
                    return _.startsWith(key, this.$_name+'.'+k+'.')
                }), (value, key) => {
                    return key.replace(this.$_name+'.'+k+'.', '')
                })
                form.$_validate(formErrors)
            })            
        },
        $_fillRecursive(jsonFormData){
            if(this.$_hidden)
                return 

            var name = this.$_name, results = [] 
            this.komponents.forEach( (item,k) => {
                var json = {}
                item.$_fillRecursive(json)
                if(item.multiFormKey)
                    json.multiFormKey = item.multiFormKey
                results.push(json)
            })
            results.forEach( (form, k) => {
                Object.keys(form).forEach( (key) => {
                    jsonFormData[name+'['+k+']'+'['+key+']'] = form[key]
                })
            })
        },
        revertFormRow(childId){
            var rowToDelete
            this.komponents.forEach( (item, k) => {
                if(item.$_getPathById(childId))
                    rowToDelete = k
            })
            if(rowToDelete || rowToDelete === 0)
                this.$delete(this.komponents, rowToDelete)
        }
    }
}
</script>
