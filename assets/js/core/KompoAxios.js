import Action from './Action'
import Alert from './Alert'

export default class KompoAxios{

    constructor(element){

        this.element = element

        this.$_komponent = element instanceof Action ? element.vue : element
        this.$_parentKompoId = this.$_komponent.kompoid

        this.$_route = element.$_data('route')
        this.$_routeMethod = element.$_data('routeMethod')
        this.$_ajaxPayload = element.$_data('ajaxPayload')

        this.$_orderableRoute = element.$_data('orderableRoute') //TODO change to $_route

        this.$_kompoAction = element.$_data('kompoAction')
        this.$_kompoMethod = element.$_data('kompoMethod')
        this.$_komposerClass = element.$_data('komposerClass')

        this.$_kompoRoute = element.$_data('kompoRoute')
    
        this.$_ajaxOptionsMethod = element.$_data('ajaxOptionsMethod') 

        this.$_sessionTimeoutMessage = element.$_data('sessionTimeoutMessage')

    }

    /****** Actions ******/ 
    $_actionAxiosRequest(){
        return this.$_axios({
            url: this.$_route, 
            method: this.$_routeMethod || 'POST', //POST when used outside PHP
            data: this.element.getPayloadForStore(),
            headers: Object.assign(
                {'X-Kompo-Id': this.$_parentKompoId}, 
                this.$_kompoAction ? { 'X-Kompo-Action': this.$_kompoAction } : {},
                this.$_kompoMethod ? { 'X-Kompo-Method': this.$_kompoMethod } : {}
            )
        })
    }
    $_submitFormAction(){
        return this.$_axios({
            url: this.$_komponent.formInfo.url, 
            method: this.$_komponent.formInfo.method,
            data: this.element.getFormData(),
            headers: Object.assign(
                {
                    'X-Kompo-Id': this.$_parentKompoId
                }, 
                this.$_komponent.formInfo.action ? {
                    'X-Kompo-Action': this.$_komponent.formInfo.action
                } : {},
                this.$_kompoMethod ? {
                    'X-Kompo-Handle': this.$_kompoMethod,
                    'X-Kompo-Action': 'handle-submit' //X-Kompo-Action above will be overwritten if this.handle
                } : {}
            )
        })
    }

    /******* Komponents *********/
    $_browseQuery(page, sort){
        return this.$_axios({
            url: this.$_komponent.queryUrl, 
            method: 'POST',
            data: this.$_komponent.preparedFormData(),
            headers: {
                'X-Kompo-Id': this.$_komponent.$_elKompoId,
                'X-Kompo-Page': page,
                'X-Kompo-Sort': sort,
                'X-Kompo-Action': 'browse-items'
            }
        })
    }
    $_orderQuery(newOrder){
        return this.$_axiosWithErrorHandling({
            url: this.$_orderableRoute, 
            method: 'POST',
            data: {
                order: newOrder
            },
            headers: {
                'X-Kompo-Id': this.$_parentKompoId
            }
        })
    }
    $_loadKomposer(){

        return this.$_axiosWithErrorHandling({
            url: this.$_route, 
            method: this.$_routeMethod,
            data: this.$_ajaxPayload,
            headers: {
                'X-Kompo-Id': this.$_parentKompoId,
                'X-Kompo-Action': 'load-komposer',
                'X-Kompo-Class': this.$_komposerClass
            }
        })
    }
    $_updatedOption(){

        return this.$_axiosWithErrorHandling({
            url: this.$_kompoRoute, 
            method: 'POST',
            headers: {
                'X-Kompo-Id': this.$_parentKompoId,
                'X-Kompo-Action': 'updated-option',
                'X-Kompo-Class': this.$_komposerClass
            }
        })
    }
    $_searchOptions(search){

        return this.$_axiosWithErrorHandling({
            url: this.$_kompoRoute, 
            method: 'POST',
            data: {
                method: this.$_ajaxOptionsMethod,
                search: search
            },
            headers: {
                'X-Kompo-Id': this.$_parentKompoId,
                'X-Kompo-Action': 'search-options'
            }
        })
    }

    /*** Internal *******/
    $_axiosWithErrorHandling(axiosRequest){

        return this.$_axios(axiosRequest)
                   .catch(e => {

                        this.$_handleAjaxError(e) 

                    })
    }
    $_axios(axiosRequest){

        return axios(axiosRequest)
    }
    $_handleAjaxError(e){
        if(e.response.status == 419 && confirm(this.$_sessionTimeoutMessage)){
            window.location.reload()
        }else{
            new Alert('Error '+e.response.status+' | '+e.response.data.message).asError().emitFrom(this.$_komponent)
        }
    }
}