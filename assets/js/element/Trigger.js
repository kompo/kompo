export default class Trigger {
	constructor(vue, trigger){

		this.vue = vue
        this.warningConfirmed = false

		this.action = trigger.action
        this.kompoid = trigger.kompoid

		this.route = trigger.route 
        this.routeMethod = trigger.routeMethod 
		this.ajaxPayload = trigger.ajaxPayload

        this.included = trigger.included
        this.handle = trigger.handle
		this.sessionTimeoutMessage = trigger.sessionTimeoutMessage
		this.redirectUrl = trigger.redirectUrl
		
		this.modalName = trigger.modalName
		this.warnBeforeClose = trigger.warnBeforeClose
		this.panelId = trigger.panelId
		this.response = trigger.response
		this.message = trigger.message
		this.alertClass = trigger.alertClass
        this.debounce = trigger.debounce

        this.page = trigger.page
		
		this.event = trigger.event
        this.emitPayload = trigger.emitPayload

		this.success = trigger.triggers ? trigger.triggers.success : []
		this.error = trigger.triggers ? trigger.triggers.error : []
	}
	run(response, parentTrigger){
        if(!this.action)
            return

		var actionFunction = this.action + 'Action'
        this[actionFunction](response, parentTrigger) 
	}
    axiosRequestAction(){
    	this.vue.$_state({ loading: true })
        this.vue.$kompo.vlToggleSubmit(this.vue.kompoid, false) //disable submit while loading

        axios({
            url: this.route, 
            method: this.routeMethod || 'POST', //POST when used outside PHP
            data: this.getPayloadForStore(),
            headers: Object.assign(
                {'X-Kompo-Id': this.vue.kompoid}, 
                this.included ? { 'X-Kompo-Includes': this.included} : {}
            )
        }).then(response => {

			this.vue.$_state({ loading: false })
            this.vue.$kompo.vlToggleSubmit(this.vue.kompoid, true)

        	this.vue.$_runTriggers(this.success, response, this)

        }).catch(error => {

            this.vue.$_state({ loading: false })

        	this.vue.$_runTriggers(this.error, error, this)
        	this.handleAjaxError(error) 

        })
    }
    refreshCatalogAction(){
        this.vue.$_state({ loading: true })
        this.vue.$kompo.vlRefreshCatalog(this.kompoid || this.vue.kompoid, this.page)
    }
    submitFormAction(){
        
        this.vue.$_state({ loading: true })
        this.vue.$_state({ isSuccess: false })
        this.vue.$_state({ hasError: false })

        this.vue.$kompo.vlRequestFormInfo(this.vue.kompoid, this.vue.$_elementId())

        if(!this.vue.formInfo.canSubmit){
            setTimeout( () => { this.submitAction() }, 100)
            return
        }

        this.vue.$kompo.vlPreSubmit(this.vue.kompoid)

        if(!this.vue.formInfo.url)
            return
        
        axios({
            url: this.vue.formInfo.url, 
            method: this.vue.formInfo.method,
            data: this.getFormData(),
            headers: Object.assign(
                {'X-Kompo-Id': this.vue.kompoid}, 
                this.handle ? {'X-Kompo-Handle': this.handle} : {}
            )
        }).then(response => {

            this.vue.$_state({ loading: false })
            this.vue.$_state({ isSuccess: true })

            this.vue.$kompo.vlSubmitSuccess(this.vue.kompoid, response)
            this.vue.$_runTriggers(this.success, response, this)

        })
        .catch(error => {

            this.vue.$_state({ loading: false })
            this.vue.$_state({ hasError: true })

            if(error.response.status == 449){
                if(confirm(error.response.data)){
                    this.warningConfirmed = true
                    this.submitAction()
                }
            }else{
                this.vue.$kompo.vlSubmitError(this.vue.kompoid, error)
                this.vue.$_runTriggers(this.error, error, this)
            }
            
        })
    }
    sortCatalogAction(){
        this.vue.$_state({ 
            activeSort: true,
            loading: true
        })
        if(this.vue.customBeforeSort)
            this.vue.customBeforeSort()

        this.vue.$kompo.vlSort(this.vue.kompoid, this.vue.$_sortValue, this.vue.$_elementId())
    }
    emitFromAction(response){
        this.vue.$_vlEmitFrom(this.event, Object.assign(
            this.emitPayload || {}, 
            this.vue.$_getJsonValue || {} 
        ))
    }
    emitDirectAction(response){
    	this.vue.$emit(this.event, response.data)
    }
    fillModalAction(response){
    	if(!this.modalName)
    		this.modalName = this.vue.kompoid ? 'modal'+this.vue.kompoid : 'vlDefaultModal'
        if(!this.panelId)
            this.panelId = this.vue.kompoid ? 'modal'+this.vue.kompoid : 'vlDefaultModal'

        this.vue.$modal.show(this.modalName, true, this.warnBeforeClose)

        this.vue.$nextTick( () => {
        	this.vue.$kompo.vlFillPanel(this.panelId, 
	            this.response || response.data.message || response.data
	        )
        })
    }
    fillPanelAction(response, parentTrigger){
        this.vue.$kompo.events.$emit('vlFillPanel' + this.panelId, response.data, parentTrigger)
    }
    addAlertAction(){
        this.vue.$modal.events.$emit('showAlert', this.message, this.alertClass)
    }
    redirectAction(response){
    	if(this.redirectUrl === true){
            setTimeout( () => {this.redirect(response.request.responseURL)},300)
        }else if(this.redirectUrl){
            setTimeout( () => {this.redirect(this.redirectUrl)},300)
        }
    }
    redirect(url){
        window.location.href = url
    }
    getPayloadForStore() {
        var store = Object.assign(
            this.ajaxPayload || {}, 
            this.vue.$_getJsonValue || {} 
        )
        return store ? {store: store} : null
    }
    getFormData() {
        var formData = new FormData(), jsonFormData = this.vue.formInfo.jsonFormData
        for ( var key in jsonFormData ) {
            formData.append(key, jsonFormData[key])
        }
        if(this.warningConfirmed)
            formData.append('vuravelConfirmed', this.warningConfirmed)
        return formData
    }
    handleAjaxError(e){
    	console.log(e)
        if(e.response.status == 419 && confirm(this.sessionTimeoutMessage)){
            window.location.reload()
        }else{
            this.vue.$modal.events.$emit('showAlert', 'Error '+e.response.status+' | '+e.response.data.message, 'vlAlertError')
        }
    }
}