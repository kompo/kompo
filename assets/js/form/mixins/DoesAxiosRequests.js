import KompoAxios from '../../core/KompoAxios'

export default {
    data(){
        return {
            $_kAxios: null
        }
    },
    created(){
        this.$_kAxios = new KompoAxios(this)
    }

}