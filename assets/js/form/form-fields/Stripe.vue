<template>
    <div>
        <vl-input v-if="withCardholder" :vcomponent="chComponent" />

        <vl-form-field v-bind="$_wrapperAttributes" class="vlMargins">
            <div class="vlFormControl vlFlex vlAlignCenter">
                <div style="width: 1px">&nbsp;</div>
                <div ref="card" style="flex:1"></div>
            </div>
        </vl-form-field>

        <vl-button :vcomponent="btnComponent" :disabled="loading" @click="createSetupIntent">
            <label v-html="$_label" />
            <vl-spinner-icon :loading="loading"/>
        </vl-button>
    </div>
</template>

<script>
import Field from '../mixins/Field'

export default {
    mixins: [Field],
    data(){
        return {
            stripe: undefined,
            elements: null,
            card: undefined,
            cardholderValue: '',
            chComponent: null,
            btnComponent: null,
            loading: false
        }
    },
    computed: {
        cardholderId(){ return this.$_elementId()+'_name' },
        cardholderLabel(){ return this.$_data('cardholderLabel') },
        cardholderName(){ return 'cardholder-name' },
        cardholderError(){ return this.$_data('cardholderError') },
        withCardholder(){
            return this.chComponent && this.cardholderLabel
        }
    },
    methods: {
        async createSetupIntent(){

            if(this.$_value){ //if card already got checked and a paymentMethod was returned
                this.$_submit()
                return
            }

            this.loading = true

            var payment_method_data = {}

            if(this.withCardholder){
                this.setCardholderValue()
                if(!this.cardholderValue){
                    this.chComponent.$_setError([this.cardholderError])
                }else{
                    payment_method_data = {
                        payment_method_data: { billing_details: { name: this.cardholderValue } }
                    }
                }
            }

            const { setupIntent, error } = await this.stripe.handleCardSetup(
                this.component.$_data('intent'), this.card, payment_method_data
            )

            this.loading = false
            
            if(error){
                this.component.$_setError([error.message])
            }else{
                this.component.$_setError(null)
                this.component.value = setupIntent.payment_method
                this.$_submit()
            }
        },
        $_fill(jsonFormData){
            jsonFormData[this.$_name] = this.$_value || ''
            if(this.withCardholder)
                jsonFormData[this.cardholderName] = this.cardholderValue
        },
        setCardholderValue(){
            var jsonFormData = {}
            this.chComponent.$_fillRecursive(jsonFormData)
            this.cardholderValue = jsonFormData[this.cardholderName]
        }
    },
    mounted(){
        let stripeScript = document.createElement('script')
        stripeScript.onload = () => {

            this.stripe = typeof Stripe !== 'undefined' ? Stripe(process.env.MIX_STRIPE_KEY) : undefined
            
            this.elements = this.stripe.elements({
                fonts: this.$_data('fontSrc') ? [ {cssSrc: this.$_data('fontSrc')}] : []
            })
            
            this.card = this.elements.create('card', { style: { base:this.$_data('styles') }})
            
            this.card.mount(this.$refs.card)

        }
        stripeScript.async = true
        stripeScript.setAttribute('src', 'https://js.stripe.com/v3/')
        document.head.appendChild(stripeScript)
    },
    created(){

        this.btnComponent = Object.assign({}, this.vcomponent)

        this.chComponent = Object.assign({}, this.vcomponent, {
            placeholder: this.cardholderLabel,
            id: this.cardholderId,
            data: {
                noLabel: true
            },
            name: this.cardholderName
        })

    }
}
</script>

