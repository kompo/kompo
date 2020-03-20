import {Element} from 'vuravel-elements'

export default {
    mixins: [ Element ],
	props: {
		vcomponent: { type: Object | Array,required: true }, //to override Object ony type in Elements
    	vuravelid: { type: String, required: true },
		index: { type: Number },
		active: { type: Boolean },
		layout: { type: String},
	},
	computed:{
		$_activeClass(){ return this.active ? 'vlActive' : '' },
		$_props(){ return this.vcomponent.components }
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
        		vuravelid: this.vuravelid,
            	is: 'Vl' + component.component,
            	vcomponent: component,
                index: this.index
        	}
        },
		$_preview(){
			if(this.$_prop('noPreview') || this.$_prop('url'))
				return

			this.$vuravel.vlPreview(this.vuravelid, this.index)
		},
		$_previewModal(arrows){

			this.$modal.insertModal(
                this.vuravelid, {
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
        this.vcomponent.$_prop = this.$_prop
        this.vcomponent.$_previewModal = this.$_previewModal
	}
}