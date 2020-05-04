export default {
	computed: {

        $_kompoInfo() { return this.$_data('X-Kompo-Info') },

        $_deliverKompoInfoOff() { return 'vlGetKomposerInfo'+this.$_elKompoId }

    },
    methods:{
        $_deliverKompoInfoOn(){
            this.$_vlOn('vlGetKomposerInfo'+this.$_elKompoId, (askerId) => {

                this.$kompo.vlDeliverKompoInfo(askerId, this.$_kompoInfo)
                
            })
        }
    },
}