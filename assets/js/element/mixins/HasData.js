export default {
    methods: {
        //obj is almost always null unless we are calling the function on another object
        $_data(data, obj){
            obj = obj || this.component
            if(_.isObject(data)){
                this.$set(obj, 'data', Object.assign(obj.data, data))
            }else{
                return _.get(obj, 'data.' + data)
            }
        }
    }
}
