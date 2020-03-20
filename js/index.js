import Element from './js/mixins/Element'

window.$ = window.jQuery = require('jquery')

const Elements = {
  	install (Vue, options = {}) {

  		const files = require.context('./js/', true, /\.vue$/i)
		files.keys().map(key => Vue.component('Vl'+key.split('/').pop().split('.')[0], files(key).default))

    	this.events = new Vue()

		Vue.prototype.$vuravel = {
			vlEmitFrom(vuravelid, event, parameters){
				Elements.events.$emit('vlEmit'+vuravelid, event, parameters)
			},
			vlSort(vuravelid, sortValue, emitterId){
				Elements.events.$emit('vlSort'+vuravelid, sortValue, emitterId)
			},
			vlPreview(vuravelid, index){
				Elements.events.$emit('vlPreview'+vuravelid, index)
			},
			vlPreSubmit(vuravelid){
	    		Elements.events.$emit('vlPreSubmit'+vuravelid)
	    	},
			vlSubmitSuccess(vuravelid, response){
	    		Elements.events.$emit('vlSubmitSuccess'+vuravelid, response)
	    	},
			vlSubmitError(vuravelid, error){
	    		Elements.events.$emit('vlSubmitError'+vuravelid, error)
	    	},
			vlRefreshCatalog(vuravelid, page){
	    		Elements.events.$emit('vlRefreshCatalog'+vuravelid, page)
	    	},
	    	vlToggle(vuravelid, toggleId){
	    		Elements.events.$emit('vlToggle'+vuravelid, toggleId)
	    	},
	    	vlUpdateErrorState(vuravelid){
	    		Elements.events.$emit('vlUpdateErrorState'+vuravelid)
	    	},
	    	vlDeliverJsonFormData(vuravelid, toComponentId){
	    		Elements.events.$emit('vlDeliverJsonFormData'+vuravelid, toComponentId)
	    	},
	    	vlToggleSubmit(vuravelid, canSubmit){
	    		Elements.events.$emit('vlToggleSubmit'+vuravelid, canSubmit)
	    	},
	    	vlFillPanel(panelId, response, parentTrigger){
	    		Elements.events.$emit('vlFillPanel'+panelId, response, parentTrigger)
	    	},
	    	vlRequestFormInfo(vuravelid, askerId){
	    		Elements.events.$emit('vlRequestFormInfo'+vuravelid, askerId)
	    	},
	    	vlDeliverFormInfo(askerId, formInfo){
	    		Elements.events.$emit('vlDeliverFormInfo'+askerId, formInfo)
	    	},
	    	events : this.events
		}

	    Vue.prototype.$modal = {
	    	show(modal, ajaxContent, warnbeforeclose){
	    		Elements.events.$emit('show', modal, ajaxContent, warnbeforeclose)
	    	},
	    	close(modal){
	    		Elements.events.$emit('close', modal)
	    	},
	    	showFill(modal, html){
	    		Elements.events.$emit('showFill' + modal, html)
	    	},
	    	insertModal(vuravelid, componentProps, modalProps){
	    		Elements.events.$emit('insertModal' + vuravelid, componentProps, modalProps)
	    	},
	    	events : this.events
	    }

    	Vue.directive('click-outside', {
			bind: function (el, binding, vnode) {
				el.clickOutsideEvent = function (event) {
					if (!(el == event.target || el.contains(event.target))) 
					    vnode.context[binding.expression](event)
				}
				document.body.addEventListener('click', el.clickOutsideEvent)
			},
			unbind: function (el) {
				document.body.removeEventListener('click', el.clickOutsideEvent)
			}
		})

		Vue.directive('turbo-click', {
			bind: function (el, binding, vnode) {
				el.turboClickEvent = function (e) {

					if(el.href == 'javascript:void(0)' || el.target == '_blank' || e.ctrlKey)
						return

					if(el == e.target || el.contains(e.target)){

						var url = el.href.split('#'),
							currentUrl = window.location.href.replace(window.location.hash, '')
						if(url.length == 2 && url[0] == currentUrl)
							return

						e.preventDefault()

						$("#vl-content").remove() //working but Vue component still visible in inspector

						axios.get(el.href).then(r => {
							
							//parse the GET response HTML
							var doc = new DOMParser().parseFromString(r.data, "text/html")

							document.title = doc.title

							//Fill in the main content section in a Panel so that Vue re-renders
							//making sure to wrap the element in a single node if it wasn't already
							var content = doc.getElementById("vl-content").children.length > 1 ? 
								('<div>'+doc.getElementById("vl-content").innerHTML+'</div>') :
									doc.getElementById("vl-content").innerHTML

							Elements.events.$emit('vlFillPanel' + 'vl-main-panel', content)

							//Change the navs content (for active state or if the content change)
							if(doc.getElementsByClassName('vl-sidebar-l').length > 0){
								Elements.events.$emit('vlFillPanel' + 'vl-sidebar-l-panel', doc.getElementsByClassName('vl-sidebar-l')[0].outerHTML)
								var oldScroll = $('.vl-sidebar-l')[0].scrollTop
								$("#vl-sidebar-l-container").remove() // was .empty() but not needed
								Elements.events.$nextTick(()=> document.getElementsByClassName('vl-sidebar-l')[0].scrollTop = oldScroll )
							}

							//Re-run scripts with the class .reloadable-script
							//Elements.events.$nextTick( () => { //nextTick not enough because of anonymous component in Panel {template: ...}
							setTimeout( () => { //TODO better solution
								Array.from(doc.getElementsByClassName('reloadable-script'))
									.forEach((script) => { eval(script.innerHTML) })
							}, 400)

							//Change the browser's url and reload if back is pressed
							window.history.pushState({url: el.href}, "", el.href)
							window.onpopstate = function(e) {location.reload()} //for back button

			            }).catch(e => {
			            	console.log('Error loading object in Panel:' + e)
			            })
			        }
				}

				if(binding.value !== false) //when v-turbo-click="false" don't bind
					document.body.addEventListener('click', el.turboClickEvent)
			},
			unbind: function (el) {
				document.body.removeEventListener('click', el.turboClickEvent)
			}
		})

	}
}
export {Elements, Element}




/*********** CATALOG ******************/


import {VueMasonryPlugin} from 'vue-masonry'

const Catalog = {
  	install (Vue, options = {}) {

  		Vue.use(VueMasonryPlugin)

		const files = require.context('./js/', true, /\.vue$/i)
		files.keys().map(key => Vue.component('Vl'+key.split('/').pop().split('.')[0], files(key).default))

	}
}
export default Catalog

/*********** FORM ******************/

import {Elements} from 'vuravel-elements'

const Form = {
  	install (Vue, options = {}) {
  		
		Vue.use(Elements)
		
		const files = require.context('./js/', true, /\.vue$/i)
		
		files.keys().map(key => {
			Vue.component('Vl'+key.split('/').pop().split('.')[0], files(key).default)
		})

	}
}
export default Form



/*********** COMPONENTS ******************/


import VuravelForm from 'vuravel-form'
import VuravelCatalog from 'vuravel-catalog'
Vue.use(VuravelForm)
Vue.use(VuravelCatalog)
/*const Vuravel = {
  	install (Vue, options = {}) {
  		
		Vue.use(VuravelForm)
		Vue.use(VuravelCatalog)

	}
}
export default Vuravel*/