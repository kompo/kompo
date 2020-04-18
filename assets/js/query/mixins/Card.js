import Element from '../../element/mixins/Element'

export default {
    mixins: [ Element ],
	props: {
		vkompo: { type: Object | Array,required: true }, //to override Object ony type in Elements
    	kompoid: { type: String, required: true },
		index: { type: Number },
		active: { type: Boolean },
		layout: { type: String},
	},
	computed:{
		$_activeClass(){ return this.active ? 'vlActive' : '' },
		$_props(){ return this.vkompo.components }
	},
	methods:{
		$_prop(propKey){ 
			return this.$_props[propKey] || '' //otherwise classes were showing undefined
		},
        activate(){
        	this.$emit('activate', this.index)
        },
        componentAttributes(component){
        	return {
        		kompoid: this.kompoid,
            	is: 'Vl' + component.component,
            	vkompo: component,
                index: this.index
        	}
        },
		$_preview(){
			if(this.$_prop('noPreview') || this.$_prop('url'))
				return

			this.$kompo.vlPreview(this.kompoid, this.index)
		},
		$_previewModal(arrows){

			this.$modal.insertModal(
                this.kompoid, {
                	is: 'VlImageModalContent',
					image: this.$_prop('image'),
					title: this.$_prop('title')
				}, {
					arrows: arrows
				}
			)
		}
	},
	created(){
        this.vkompo.$_prop = this.$_prop
        this.vkompo.$_previewModal = this.$_previewModal
	}
}