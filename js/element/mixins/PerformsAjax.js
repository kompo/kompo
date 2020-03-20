export default {
    data(){
        return {
            DEFAULT_MODAL: 'default',
            MODAL_PANEL: 'modal-panel'
        }
    },

    computed: {
        $_selectedModal(){
            return this.$_data('inModal') === true ? this.DEFAULT_MODAL : this.$_data('inModal')
        },
        $_panelId(){
            return this.$_data('panelId')
                || ( this.$_selectedModal == this.DEFAULT_MODAL ? this.MODAL_PANEL : this.$_selectedModal)
        },
        $_route(){ return this.$_data('route') }, //SelectUpdatable is still using these
        $_routeMethod(){ return this.$_data('routeMethod') }, //SelectUpdatable is still using these
        $_loads(){ return this.$_data('loads') },
        $_post(){ return this.$_data('post') },
        $_loadsView(){ return this.$_data('loadsView') },
        $_ajaxPayload(){ return this.$_data('ajaxPayload') },
        $_revertsPanel(){ return this.$_data('revertsPanel') },
        $_revertsFormRow(){ return this.$_data('revertsFormRow') },
        $_sessionTimeoutMessage(){ return this.$_data('sessionTimeoutMessage') }, //SelectUpdatable is still using these

        $_warnBeforeClose(){ return this.$_data('warnBeforeClose') }
    }
}
