import KompoAxios from './KompoAxios'
import Alert from './Alert'

export default class Action {
	constructor(action, vue){

        this.actionData = action.data
		this.vue = vue
        
        this.warningConfirmed = false

		this.actionType = action.actionType
        this.interactions = action.interactions

        this.$_kAxios = new KompoAxios(this)

	}
    $_data(key){
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

        this.$_kAxios.$_actionAxiosRequest()
        .then(response => {

			this.vue.$_state({ loading: false })
            this.vue.$kompo.vlToggleSubmit(this.vue.kompoid, true)

            this.vue.$_runInteractionsOfType(this, 'success', response)


        }).catch(error => {

            this.vue.$_state({ loading: false })

        	this.vue.$_runInteractionsOfType(this, 'error', error)
        	this.$_kAxios.$_handleAjaxError(error) 

        })
    }
    refreshQueryAction(){
        this.vue.$_state({ loading: true })
        this.vue.$kompo.vlRefreshQuery(this.kompoid || this.vue.kompoid, this.$_data('page'))
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
        
        this.$_kAxios.$_submitFormAction().then(response => {

            this.vue.$_state({ loading: false })
            this.vue.$_state({ isSuccess: true })

            this.vue.$kompo.vlSubmitSuccess(this.vue.kompoid, response, this.vue)
            this.vue.$_runInteractionsOfType(this, 'success', response)

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
                this.vue.$_runInteractionsOfType(this, 'error', error)
            }
            
        })
    }
    sortQueryAction(){
        this.vue.$_state({ 
            activeSort: true,
            loading: true
        })
        if(this.vue.customBeforeSort)
            this.vue.customBeforeSort()

        this.vue.$kompo.vlSort(this.vue.kompoid, this.vue.$_sortValue, this.vue.$_elKompoId)
    }
    emitFromAction(response){
        this.vue.$_vlEmitFrom(this.$_data('event'), Object.assign(
            this.$_data('emitPayload') || {}, 
            this.vue.$_getJsonValue || {} 
        ))
    }
    emitDirectAction(response){
    	this.vue.$emit(this.$_data('event'), response.data)
    }
    fillModalAction(response){
    	var modalName = this.$_data('modalName') || (this.vue.kompoid ? 'modal'+this.vue.kompoid : 'vlDefaultModal')
        var panelId = this.$_data('panelId') || (this.vue.kompoid ? 'modal'+this.vue.kompoid : 'vlDefaultModal')

        this.vue.$modal.show(modalName, true, this.$_data('warnBeforeClose'))

        this.vue.$nextTick( () => {
        	this.vue.$kompo.vlFillPanel(panelId, response.data.message || response.data)
        })
    }
    fillPanelAction(response, parentAction){
        this.vue.$kompo.vlFillPanel(this.$_data('panelId'), response.data, parentAction.$_data('included'))
    }
    addAlertAction(){
        new Alert().asObject(this.$_data('alert')).emitFrom(this.vue)
    }
    fillAlertAction(response){
        new Alert().asObject({
            ...this.$_data('alert'),
            message: response.data
        }).emitFrom(this.vue)
    }
    redirectAction(response){
    	if(this.$_data('redirectUrl') === true){
            setTimeout( () => { this.redirect(response.request.responseURL) }, 300)
        }else if(this.$_data('redirectUrl')){
            setTimeout( () => { this.redirect(this.$_data('redirectUrl')) }, 300)
        }
    }
    redirect(url){
        window.location.href = url
    }
    getPayloadForStore() {
        return Object.assign(
            this.$_data('ajaxPayload') || {}, 
            this.vue.$_getJsonValue || {} 
        )
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
}