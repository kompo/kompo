export default {
    methods: {
        //obj is almost always null unless we are calling the function on another object
        $_data(data, obj){
            obj = obj || this.component
            if(data.constructor === Array){
                obj.data = Object.assign(obj.data, data)
            }else{
                return _.get(obj, 'data.' + data)
            }
        }
    }
}
