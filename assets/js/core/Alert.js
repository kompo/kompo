export default class Alert {
	constructor(message, iconClass, alertClass){

        this.message = message

        this.iconClass = iconClass

        this.alertClass = alertClass

	}
    asObject(alert){ //lazy... TODO: refactor
        this.message = alert.message

        this.iconClass = alert.iconClass

        this.alertClass = alert.alertClass
        return this
    }
    asError(){
        this.iconClass = 'icon-attention'
        this.alertClass = 'vlAlertError'
        return this
    }
    emitFrom(emittor){
        emittor.$modal.events.$emit('showAlert', this)
    }
}