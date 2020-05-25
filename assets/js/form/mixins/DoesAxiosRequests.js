import KompoAxios from '../../core/KompoAxios'

export default {
    data(){
        return {
            //$_kAxios: null
        }
    },
    computed: {
    	$_kAxios() {
    		return new KompoAxios(this)
    	}
    },
    created(){
        //this.$_kAxios = new KompoAxios(this)
    }

}