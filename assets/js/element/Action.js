export default class Action {
	constructor(action, vue){

        this.actionData = action.data
		this.vue = vue
        
        this.warningConfirmed = false

		this.actionType = action.actionType
        this.interactions = action.interactions

        /*this.kompoid = trigger.kompoid

        this.handle = trigger.handle
		this.sessionTimeoutMessage = trigger.sessionTimeoutMessage
		this.redirectUrl = trigger.redirectUrl
		
		this.modalName = trigger.modalName
		this.warnBeforeClose = trigger.warnBeforeClose
		this.message = trigger.message
		this.alertClass = trigger.alertClass
        this.debounce = trigger.debounce

        this.page = trigger.page
		
		this.event = trigger.event
        this.emitPayload = trigger.emitPayload*/
	}
    aData(key){
        return this.actionData[key] || null
    }
	run(response, parentAction){
        if(!this.actionType)
            return

		var actionFunction = this.actionType + 'Action'
        this[actionFunction](response, parentAction) 
	}
    axiosRequestAction(){
    	this.vue.$_state({ loading: true })
        this.vue.$kompo.vlToggleSubmit(this.vue.kompoid, false) //disable submit while loading

        axios({
            url: this.aData('route'), 
            method: this.aData('routeMethod') || 'POST', //POST when used outside PHP
            data: this.getPayloadForStore(),
            headers: Object.assign(
                {'X-Kompo-Id': this.vue.kompoid}, 
                this.aData('kompoAction') ? { 'X-Kompo-Action': this.aData('kompoAction')} : {},
                this.aData('kompoMethod') ? {'X-Kompo-Method': this.aData('kompoMethod')} : {}
            )
        }).then(response => {

			this.vue.$_state({ loading: false })
            this.vue.$kompo.vlToggleSubmit(this.vue.kompoid, true)

        	//this.vue.$_runAllActionsIn(this.success, response, this)
            if(this.interactions && this.interactions.length)
                this.interactions.forEach( interaction => {
                    if(interaction.interactionType == 'success')
                        this.vue.$_runAction(interaction.action, response, this)
                })


        }).catch(error => {

            this.vue.$_state({ loading: false })

        	//this.vue.$_runAllActionsIn(this.error, error, this)
            if(this.interactions && this.interactions.length)
                this.interactions.forEach( interaction => {
                    if(interaction.interactionType == 'error')
                        this.vue.$_runAction(interaction.action, error, this) 
                })
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

        this.vue.$kompo.vlRequestFormInfo(this.vue.kompoid, this.vue.$_elKompoId)

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
                {
                    'X-Kompo-Id': this.vue.kompoid
                }, 
                this.vue.formInfo.action ? {
                    'X-Kompo-Action': this.vue.formInfo.action
                } : {},
                this.handle ? {
                    'X-Kompo-Handle': this.handle,
                    'X-Kompo-Action': 'handle-submit' //X-Kompo-Action above will be overwritten if this.handle
                } : {}
            )
        }).then(response => {

            this.vue.$_state({ loading: false })
            this.vue.$_state({ isSuccess: true })

            this.vue.$kompo.vlSubmitSuccess(this.vue.kompoid, response)
            this.vue.$_runAllActionsIn(this.success, response, this)

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
                this.vue.$_runAllActionsIn(this.error, error, this)
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

        this.vue.$kompo.vlSort(this.vue.kompoid, this.vue.$_sortValue, this.vue.$_elKompoId)
    }
    emitFromAction(response){
        this.vue.$_vlEmitFrom(this.event, Object.assign(
            this.emitPayload || {}, 
            this.vue.$_getJsonValue || {} 
        ))
    }
    emitDirectAction(response){
    	this.vue.$emit(this.aData('event'), response.data)
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
    fillPanelAction(response, parentAction){
        this.vue.$kompo.events.$emit('vlFillPanel' + this.aData('panelId'), response.data, parentAction.aData('included'))
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
        if(e.response.status == 419 && confirm(this.sessionTimeoutMessage)){
            window.location.reload()
        }else{
            this.vue.$modal.events.$emit('showAlert', 'Error '+e.response.status+' | '+e.response.data.message, 'vlAlertError')
        }
    }
}