import {VueMasonryPlugin} from 'vue-masonry'

window.$ = window.jQuery = require('jquery')

const Kompo = {
  	install (Vue, options = {}) {

  		const files = require.context('./js/', true, /\.vue$/i)
		files.keys().map(key => Vue.component('Vl'+key.split('/').pop().split('.')[0], files(key).default))

    	this.events = new Vue()
		
		Vue.use(VueMasonryPlugin)

		Vue.prototype.$kompo = {
			vlEmitFrom(kompoid, event, parameters){
				Kompo.events.$emit('vlEmit'+kompoid, event, parameters)
			},
			vlSort(kompoid, sortValue, emitterId){
				Kompo.events.$emit('vlSort'+kompoid, sortValue, emitterId)
			},
			vlPreview(kompoid, index){
				Kompo.events.$emit('vlPreview'+kompoid, index)
			},
			vlPreSubmit(kompoid){
	    		Kompo.events.$emit('vlPreSubmit'+kompoid)
	    	},
			vlSubmitSuccess(kompoid, response, submitKomponent){
	    		Kompo.events.$emit('vlSubmitSuccess'+kompoid, response, submitKomponent)
	    	},
			vlSubmitError(kompoid, error){
	    		Kompo.events.$emit('vlSubmitError'+kompoid, error)
	    	},
			vlBrowseQuery(kompoid, page){
	    		Kompo.events.$emit('vlBrowseQuery'+kompoid, page)
	    	},
	    	vlRefreshKomposer(kompoid, url, payload){
	    		Kompo.events.$emit('vlRefreshKomposer'+kompoid, url, payload)
	    	},
	    	vlToggle(kompoid, toggleId){
	    		Kompo.events.$emit('vlToggle'+kompoid, toggleId)
	    	},
	    	vlUpdateErrorState(kompoid){
	    		Kompo.events.$emit('vlUpdateErrorState'+kompoid)
	    	},
	    	vlDeliverJsonFormData(kompoid, toComponentId){
	    		Kompo.events.$emit('vlDeliverJsonFormData'+kompoid, toComponentId)
	    	},
	    	vlToggleSubmit(kompoid, canSubmit){
	    		Kompo.events.$emit('vlToggleSubmit'+kompoid, canSubmit)
	    	},
	    	vlFillPanel(panelId, response, included){
	    		Kompo.events.$emit('vlFillPanel'+panelId, response, included)
	    	},
	    	vlRequestFormInfo(kompoid, askerId){
	    		Kompo.events.$emit('vlRequestFormInfo'+kompoid, askerId)
	    	},
	    	vlDeliverFormInfo(askerId, formInfo){
	    		Kompo.events.$emit('vlDeliverFormInfo'+askerId, formInfo)
	    	},
	    	vlGetKomposerInfo(komposerId, askerId){
	    		Kompo.events.$emit('vlGetKomposerInfo'+komposerId, askerId)
	    	},
	    	vlDeliverKompoInfo(askerId, kompoInfo){
	    		Kompo.events.$emit('vlDeliverKompoInfo'+askerId, kompoInfo)
	    	},
	    	events : this.events
		}

	    Vue.prototype.$modal = {
	    	show(modal, ajaxContent, warnbeforeclose){
	    		Kompo.events.$emit('show', modal, ajaxContent, warnbeforeclose)
	    	},
	    	close(modal){
	    		Kompo.events.$emit('close', modal)
	    	},
	    	showFill(modal, html){
	    		Kompo.events.$emit('showFill' + modal, html)
	    	},
	    	insertModal(kompoid, componentProps, modalProps){
	    		Kompo.events.$emit('insertModal' + kompoid, componentProps, modalProps)
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

							Kompo.events.$emit('vlFillPanel' + 'vl-main-panel', content)

							//Change the navs content (for active state or if the content change)
							if(doc.getElementsByClassName('vl-sidebar-l').length > 0){
								Kompo.events.$emit('vlFillPanel' + 'vl-sidebar-l-panel', doc.getElementsByClassName('vl-sidebar-l')[0].outerHTML)
								var oldScroll = $('.vl-sidebar-l')[0].scrollTop
								$("#vl-sidebar-l-container").remove() // was .empty() but not needed
								Kompo.events.$nextTick(()=> document.getElementsByClassName('vl-sidebar-l')[0].scrollTop = oldScroll )
							}

							//Re-run scripts with the class .reloadable-script
							//Kompo.events.$nextTick( () => { //nextTick not enough because of anonymous component in Panel {template: ...}
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
export default Kompo

Vue.use(Kompo)