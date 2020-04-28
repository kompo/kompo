<template>
    <div v-bind="queryAttributes" class="vlQuery">

        <div class="vlFlex">
            <vl-filters v-bind="filtersAttributes('Left')" />

            <div class="vlQueryWrapper">
                <vl-filters v-bind="filtersAttributes('Top')" />
                <div class="vlQueryInner">
                    <component v-if="topPagination" @browse="browseQuery" 
                        v-bind="paginationAttributes" />

                    <div v-if="isTableLayout" class="vlTableWrapper"><!-- TableWrapper useful for various CSS tricks, ex: border-radius -->
                        <table class="w-full table vlTable">
                            <vl-table-headers :headers="headers" :kompoid="$_elKompoId" />
                            <component v-bind="layoutAttributes" />
                        </table>
                    </div>

                    <component v-else v-bind="layoutAttributes" />

                    <component v-if="bottomPagination" @browse="browseQuery" 
                        v-bind="paginationAttributes" />
                </div>
                <vl-filters v-bind="filtersAttributes('Bottom')" />
            </div>

            <vl-filters v-bind="filtersAttributes('Right')" />
        </div>

        <vl-support-modal 
            :kompoid="$_elKompoId" 
            @refresh="refreshItem"
            @previous="previewPrevious"
            @next="previewNext" />

    </div>
</template>

<script>
import Element from '../element/mixins/Element'
import DoesAxiosRequests from '../form/mixins/DoesAxiosRequests'
import IsKomposer from '../mixins/IsKomposer'

export default {
    mixins: [Element, IsKomposer, DoesAxiosRequests],
    props: {
        kompoid: { type: String, required: false }
    },
    data: () => ({
        currentPage: 1,
        currentSort: '',
        filters: [],
        cards: [],
        pagination: null,
        headers: [],
        cardsKey: '',
        previewIndex: null
    }),
    created() {
        this.cardsKey = 'cards' + this.vkompo.id
        this.filters = this.vkompo.filters
        this.cards = this.getCards(this.component)
        this.pagination = this.getPagination(this.component)
        this.headers = this.component.headers
    },
    computed: {
        filtersPlacement(){ return [ 'top', 'left', 'bottom', 'right' ] },
        queryAttributes(){
            return {
                ...this.$_defaultElementAttributes,
                class: this.$_phpClasses,
                style: this.$_elementStyles
            }
        },
        layoutAttributes(){
            return {
                is: this.layoutComponent,
                vkompo: this.component,
                kompoid: this.$_elKompoId,
                cards: this.cards,
                headers: this.headers,
                noItemsFound: this.noItemsFound,
                key: this.cardsKey
            }
        },
        paginationAttributes(){
            return {
                is: this.paginationStyle,
                pagination: this.pagination,
                class: this.paginationClass
            }
        },
        queryUrl(){ return this.$_data('browseUrl') },
        hasPagination() { return this.component.hasPagination },
        topPagination(){ return this.hasPagination && this.component.topPagination },
        bottomPagination(){ return this.hasPagination && this.component.bottomPagination },
        leftPagination(){ return this.hasPagination && this.component.leftPagination },
        paginationStyle() { return 'VlPagination' + this.component.paginationStyle },
        paginationClass(){ return this.$_classString(
            [this.leftPagination ? '' : 'vlJustifyEnd']
            .concat([this.topPagination ? 'vlPaginationT' : ''])
            .concat([this.bottomPagination ? 'vlPaginationB' : ''])
        ) },
        layoutComponent(){ return this.hasItems ? 'vl-' + this.component.layout : this.noItemsComponent },
        isTableLayout(){ return this.component.layout.indexOf('Table') > -1 },
        hasItems(){ return this.cards.length > 0 },
        noItemsFound(){ return this.component.noItemsFound },
        noItemsComponent(){ return this.isTableLayout ? 'vl-table-no-items' : 'vl-no-items' },
    },
    methods: {
        getCards(query){ return this.getPagination(query).data },
        getPagination(query){ return query.query },
        filtersAttributes(placement){
            return {
                filters: this.filters[placement.toLowerCase()],
                placement: placement,
                kompoid: this.$_elKompoId
            }
        },
        getJsonFormData(jsonFormData){
            this.$_fillRecursive(jsonFormData)
            return jsonFormData
        },
        preparedFormData(){
            var formData = new FormData(), jsonFormData = this.getJsonFormData({})
            for ( var key in jsonFormData ) {
                formData.append(key, jsonFormData[key])
            }
            return formData
        },
        refreshItem(index){
            this.browseQuery() //temporary need to make back-end
        },
        preview(index){
            this.previewIndex = index
            this.cards[this.previewIndex].$_previewModal(this.cards.length > 1)
        },
        previewPrevious(){
            this.preview(this.previewIndex == 0 ? this.cards.length - 1 : this.previewIndex - 1)
        },
        previewNext(){
            this.preview(this.previewIndex == this.cards.length - 1 ? 0 : this.previewIndex + 1)
        },
        browseQuery(page) {
            this.currentPage = page || this.currentPage
            this.$_kAxios.$_browseQuery(this.currentPage, this.currentSort).then(r => {
                this.$_state({ loading: false })
                //this.pagination = this.getPagination(r.data)
                //Vue.set(this, 'cards', this.getCards(r.data))
                this.pagination = r.data
                Vue.set(this, 'cards', r.data.data)
                this.cardsKey += 1 //to re-render cards
            })
            .catch(e => {
                if (e.response.status == 422){
                    this.$_validate(e.response.data.errors)
                }else{
                    this.$modal.showFill('modal'+this.$_elKompoId, 
                        '<div>Error '+e.response.status+' | '+e.response.data.message+'</div>')
                }

                this.$_state({ loading: false })
            })
        },
        $_attachEvents(){
            this.$_vlOn('vlEmit'+this.$_elKompoId, (eventName, eventPayload) => {
                this.$emit(eventName, eventPayload)
                if(this.kompoid)
                    this.$_vlEmitFrom(eventName, eventPayload)
            })
            this.$_vlOn('vlRefreshQuery'+this.$_elKompoId, (page) => {
                this.currentPage = page ? page : this.currentPage
                this.browseQuery()
            })
            this.$_vlOn('vlSort'+this.$_elKompoId, (sortValue, emitterId) => {
                this.currentSort = sortValue == this.currentSort ? '' : sortValue
                this.currentPage = 1
                this.$_resetSort(emitterId)
                this.browseQuery()
            })
            this.$_vlOn('vlToggle'+this.$_elKompoId, (toggleId) => {
                this.$_toggle(toggleId)
            })
            this.$_vlOn('vlPreview'+this.$_elKompoId, (index) => {
                this.preview(index)
            })
            this.$_deliverKompoInfoOn()
        },
        $_destroyEvents(){
            this.$_vlOff([
                'vlEmit'+this.$_elKompoId,
                'vlRefreshQuery'+this.$_elKompoId,
                'vlSort'+this.$_elKompoId,
                'vlToggle'+this.$_elKompoId,
                'vlPreview'+this.$_elKompoId,
                this.$_deliverKompoInfoOff
            ])
        },
        $_fillRecursive(jsonFormData){
            this.filtersPlacement.forEach(placement => 
                this.filters[placement].forEach( item => item.$_fillRecursive(jsonFormData) )
            )
        },
        $_resetSort(emitterId){
            this.filtersPlacement.forEach(placement => 
                this.filters[placement].forEach( item => item.$_resetSort(emitterId) )
            )
            this.headers.forEach( item => item.$_resetSort(emitterId) )
        },
        $_state(state){
            this.filtersPlacement.forEach(placement => 
                this.filters[placement].forEach( item => item.$_state(state) )
            )
        },
        $_toggle(toggleId){
            this.filtersPlacement.forEach(placement => 
                this.filters[placement].forEach( item => item.$_toggle(toggleId) )
            )
        },
        $_validate(errors) {
            this.filtersPlacement.forEach(placement => 
                this.filters[placement].forEach( item => item.$_validate(errors) )
            )
        },

    }
}

</script>
