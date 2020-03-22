import Element from '../../element/mixins/Element'

export default {
    mixins: [ Element ],
    props: {
        cards: { type: Array, required: true},
        kompoid: { type: String, required: true }
    },
    data: () => ({
        activeIndex: null,
        items: [],
        layoutKey: 1,
        sortingDisabled: false
    }),
    created() {
        this.items = this.cards
    },
    computed:{
        $_noItemsFound(){ return this.component.noItemsFound },
        $_hasItems(){ return this.items.length > 0 },
        $_orderable(){ return this.component.orderable },
        $_orderableRoute(){ return this.$_data('orderableRoute') },
        $_sortingAttributes(){
            return {
                disabled: this.sortingDisabled,
                list: this.items
            }
        },
        $_sortingEvents(){
            return {
                change: this.change
            }
        }
    },
    methods:{
        $_attributes(item, index) { return this.$_defaultLayoutAttributes(item, index) },
        $_defaultLayoutAttributes(item, index) {
            return {
                key: item.id || index,
                index: parseInt(index),
                active: this.activeIndex == index,
                is: 'Vl' + item.component,
                vcomponent: item,
                kompoid: this.kompoid,
                layout: this.component.layout
            }
        },
        activate(index){
            this.activeIndex = (index == this.activeIndex) ? null : index
        },
        change(event){
            this.layoutKey += 1 //<template v-for><component> wasn't rendering without it...
            if(this.$_orderable){

                var minOrder = _.minBy(this.items, 'order')
                var newOrder = _.map(this.items, (item, k) => {
                    return {
                        id: item.id,
                        order: minOrder.order + k
                    }
                })

                this.sortingDisabled = true

                axios({
                    url: this.$_orderableRoute, 
                    method: 'POST',
                    data: {
                        order: newOrder
                    },
                    headers: {
                        'X-Kompo-Id': this.component.id
                    }
                }).then(r => {
                    this.sortingDisabled = false
                })
                .catch(e => this.$_handleAjaxError(e) )
            }
        }
    }
}