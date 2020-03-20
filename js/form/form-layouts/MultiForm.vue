<template>
    <div v-bind="$_layoutWrapperAttributes" v-show="!$_hidden">
        <vl-form-label :component="component" />
        <draggable 
            v-model="components" 
            handle=".js-row-move"
            :style="$_elementStyles">
            <vl-rows
                v-for="(comp,index) in components"
                :vcomponent="comp"
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
import Field from '../mixins/Field' //not used as a mixin, just for $_name

export default {
    mixins: [Layout],
    components: { draggable },
    data: () => ({
        name: ''
    }),
    created(){
        this.name = Field.computed.$_name.call(this)
    },
    methods:{
        addRow(){
            axios({
                url: this.$_route, 
                method: 'POST',
                data: this.$_ajaxPayload,
                headers: {
                    'X-Vuravel-Id': this.vuravelid
                }
            })
            .then(r => {
                this.components.push(r.data)
            })
            .catch(e => {
                this.$_handleAjaxError(e) 
            })
        },
        $_validate(errors) {

            var ownErrors = _.pickBy(errors, (value, key) => {
                return _.startsWith(key, this.name+'.')
            })

            this.components.forEach( (form,k) => {
                var formErrors = _.mapKeys(_.pickBy(ownErrors, (value, key) => {
                    return _.startsWith(key, this.name+'.'+k+'.')
                }), (value, key) => {
                    return key.replace(this.name+'.'+k+'.', '')
                })
                form.$_validate(formErrors)
            })            
        },
        $_fillRecursive(jsonFormData){
            if(this.$_hidden)
                return 

            var name = this.name, results = [] 
            this.components.forEach( (item,k) => {
                var json = {}
                item.$_fillRecursive(json)
                if(item.recordKey)
                    json.id = item.recordKey
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
            this.components.forEach( (item, k) => {
                if(item.$_getPathById(childId))
                    rowToDelete = k
            })
            if(rowToDelete || rowToDelete === 0)
                this.$delete(this.components, rowToDelete)
        }
    }
}
</script>
